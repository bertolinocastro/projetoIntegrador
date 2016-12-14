
#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>

// Macro para limpeza do conteúdo escrito na tela em terminais
#define limpaTela (Serial.write( 27 ),Serial.print( "[2J" ),Serial.write( 27 ),Serial.print( "[H" ))

#define min(_x, _y) ( _x > _y ? _y : _x )
#define deg2rad( _x ) (_x*PI/180.0)

/*
 *  COMANDO PARA ABERTURA DE FIREWALL: sudo iptables -A OUTPUT -p tcp -m tcp --dport 80 -j ACCEPT
 */

//#define TERMINAL // Caso o serial seja analisado via terminal
#define OPENGL     // Caso o serial seja analisado via GUI

#define _SSID "d4c20c"    // Nome da rede wifi
#define _PASS "271850487" // Senha da rede wifi
/*
#define _SSID "LAB_ADS"    // Nome da rede wifi
#define _PASS "l.ads@senai" // Senha da rede wifi
*/
#define _SCRIPT "http://192.168.0.15/onbusV5.2_ESP/" // URI do servidor (raiz)
#define ENVIO _SCRIPT"envioSolicitacao/"
#define INFORMAPTS _SCRIPT"inserePontosPEsp/"

#define _ID_BUS 1 //.......... Identificador do ônibus - primary key da tabela 'onibus'
#define QTDMAX 30 //.......... Lotação máxima do ônibus

#define qtdPontos (15 + 1) //... Quantidade de pontos de ônibus durante o trajeto

#define interEntrePts 45.0 // Intervalo em segundos de descolamento entre o inicio e fim do trajeto do ônibus

#define LED_ENTRADA  14 //.... Porta D5
#define LED_SAIDA    12 //.... Porta D6
#define PUSH_ENTRADA 13 //.... Porta D7
#define PUSH_SAIDA   15 //.... Porta D8
#define LED_ALERTA_1  4 //.... Porta D2
#define LED_ALERTA_2  5 //.... Porta D1

#define LED_DA_ESP 2    //.... Led azul

/*
 * Definindo tipo de dado dos pares coordenados
 */

typedef struct{
  float lat, lon;
} coord;

/*
 * Definindo tipo de dado dos pontos e se existem solicitações
 */

typedef struct{
  coord pos;
  boolean solicitado;
} pontosSolicitados;


void criaVetPontos( void ); // Cria o vetor de pontos disponíveis e os inicializa.

float harversine( float lat1, float lon1, float lat2, float lon2 ); // Calcula distância em metros
short pontoProx( void ); // Verifica qual o ponto mais próximo atualmente
void verificarPontoSolicitado( void );

void verificacaoPeriodica( void ); // Verificações periódicas predefinidas

bool informaPontosAoServidor( void ); // Anuncia ao servidor a quantidade de pontos e o inicio e fim do trajeto

void conectaServidor( void ); // Conecta ao servidor
void filtraDados( String data ); // Filtra os dados recebidos do servidor

void contaEntrada( void ); // Contador da entrada de passageiros
void contaSaida( void ); // Contador da saída de passageiros
void piscaAlerta( void ); // Executa alerta de parada no ponto

ESP8266WiFiMulti wifi; // Criando instância de conexão à internet

uint8_t qtd = 0; // Criando variável contadora global da quantidade de passageiros
/*
const coord
  comeco = {
  -12.9339072, -38.386364 // Coordenadas iniciais do trajeto
}, fim = {
  -12.944772 , -38.385004 // Coordenadas finais do trajeto
};
*/
const coord
  comeco = {
  -12.885862, -38.374052 // Coordenadas iniciais do trajeto
}, fim = {
  -12.954955, -38.385003 // Coordenadas finais do trajeto
};

coord posicaoAct = comeco;

pontosSolicitados pontos[qtdPontos];// Coordenadas - à partir do início do trajeto - de todos os pontos de ônibus em linha reta até o fim.


// Distâncias parciais angulares
const float distAngLat = (fim.lat - comeco.lat)/qtdPontos;
const float distAngLon = (fim.lon - comeco.lon)/qtdPontos;

// Velocidades parciais angulares
const float velLatAn = (fim.lat - comeco.lat)/interEntrePts;
const float velLonAn = (fim.lon - comeco.lon)/interEntrePts;

// Velocidade linear média
const float velLinM = harversine(comeco.lat, comeco.lon, fim.lat, fim.lon)/interEntrePts;

// Variáveis controladoras do tempo
long int tempoVer     = 0;
long int tempoCon     = 0;
long int tempoEntPush = 0;
long int tempoSaiPush = 0;
long int tempoAlerta  = 0;
long int tempoIniViag = 0;

// Variáveis controladoras do estado dos botões de entrada e saída
boolean estadoEntrada = LOW;
boolean estadoSaida   = LOW;

boolean estadoEntradaAct;
boolean estadoSaidaAct;

// Variáveis controladoras do estado dos Leds de alerta
boolean estadoAlerta1 = LOW;
boolean estadoAlerta2 = LOW;

// Variável controladora da solicitação de parada
boolean emAlerta = false;

// Variavel controladora do envio do tempo ao ponto
int avisaPonto = 1;

void setup() {
  pinMode( LED_BUILTIN, OUTPUT );
  pinMode( LED_DA_ESP, OUTPUT );
  pinMode( PUSH_ENTRADA, INPUT );
  pinMode( PUSH_SAIDA, INPUT );
  pinMode( LED_ENTRADA, OUTPUT );
  pinMode( LED_SAIDA, OUTPUT );
  pinMode( LED_ALERTA_1, OUTPUT );
  pinMode( LED_ALERTA_2, OUTPUT );

  digitalWrite( LED_BUILTIN, HIGH ); // Desligar o LED embutido da Node (vermelho)
  digitalWrite( LED_DA_ESP, HIGH );  // Desligar o LED embutido da ESP  (azul)
  
  Serial.begin(115200);
  
  criaVetPontos();

  #ifdef TERMINAL
    limpaTela;
  #endif
  for(uint8_t t = 4; t > 0; --t){
    #ifdef TERMINAL
      Serial.printf("Limpando buffer... %d\n\r", t);
    #endif
    Serial.flush();
    delay(1000);
  }
  
  wifi.addAP(_SSID, _PASS);

  #ifdef TERMINAL
    Serial.printf( "\nContectado à rede WiFi!\n\n\n\r" );
    Serial.print( "Distância total do percurso:     " ); Serial.print( harversine( fim.lat, fim.lon, comeco.lat, comeco.lon ), 10 );Serial.printf( "\n\r" );
    Serial.print( "Velocidade Latitudinal  Angular: " ); Serial.print( velLatAn, 10 );Serial.printf( "\n\r" );
    Serial.print( "Velocidade Longitudinal Angular: " ); Serial.print( velLonAn, 10 );Serial.printf( "\n\r" );
    Serial.printf( "Avisando ao servidor...\n\r" );
  #endif
  
  digitalWrite( LED_BUILTIN, LOW );
  while( !informaPontosAoServidor() )
    #ifdef TERMINAL
      Serial.printf( "Tentando novamente...\n\r" ),
    #endif
    delay( 200 );
  digitalWrite( LED_BUILTIN, HIGH );

  #ifdef TERMINAL
    Serial.printf( "Avisado!\n\r" );
  #endif
  
  digitalWrite( LED_BUILTIN, LOW );
  conectaServidor();
  digitalWrite( LED_BUILTIN, HIGH );
  
  #ifdef TERMINAL
    Serial.printf( "Viagem iniciada!\n\r" );
  #endif

  #ifdef OPENGL
    Serial.printf( "VEL:%03d\n", (int)velLinM );
    Serial.printf( "QTP:%03d\n", qtdPontos );
    Serial.printf( "TMP:%03d\n", (int)interEntrePts );
  #endif
  
  tempoIniViag = millis();
}

void loop() {
  if( millis() > tempoVer + 100 ) verificacaoPeriodica(), verificarPontoSolicitado(), tempoVer = millis();
  if( millis() > tempoCon + 3000 ){
    digitalWrite( LED_BUILTIN, LOW );
    conectaServidor();
    digitalWrite( LED_BUILTIN, HIGH );
    tempoCon = millis();
  }

  if( millis() > tempoEntPush + 100 ){
    contaEntrada();
    tempoEntPush = millis();
  }
  
  if( millis() > tempoSaiPush + 100 ){
    contaSaida();
    tempoSaiPush = millis();
  }

  if( emAlerta ){
    if( millis() > tempoAlerta + 100 ){
      piscaAlerta();
      tempoAlerta = millis();
    }  
  }else{
    estadoAlerta1 = estadoAlerta2 = LOW;
    digitalWrite( LED_ALERTA_1, estadoAlerta1 );
    digitalWrite( LED_ALERTA_2, estadoAlerta2 );
  }
  
}

void criaVetPontos( void ){
  for(short i = 0; i < qtdPontos; ++i){
    pontos[i] = {
      {
        comeco.lat + distAngLat*i,
        comeco.lon + distAngLon*i
      },
      false
    };
    #ifdef TERMINAL
      Serial.printf( "Ponto %d: ", i ); Serial.print( pontos[i].pos.lat, 10 );Serial.print( pontos[i].pos.lon, 10 );Serial.printf("\n\r");
    #endif
  }
      
}

bool informaPontosAoServidor( void ){
  bool sucesso = false;
  if(wifi.run() == WL_CONNECTED){ // Caso consiga conectar ao roteador
    HTTPClient http;
    String dados  = INFORMAPTS;
           dados += "?id="  + String(_ID_BUS);
           dados += "&lai=" + String(comeco.lat, 10);
           dados += "&loi=" + String(comeco.lon, 10);
           dados += "&laf=" + String(fim.lat, 10);
           dados += "&lof=" + String(fim.lon, 10);
           dados += "&qtd=" + String(qtdPontos);
    
    http.begin( dados );
    #ifdef TERMINAL  
      Serial.printf( "Requisitando servidor...\n\r" );
    #endif
    int httpCode = http.GET(); // Envia cabeçalho HTTP e retorna falha ou sucesso
  
    if( httpCode > 0 ){ // Caso consiga fazer o Request ao servidor.  
      if( httpCode == HTTP_CODE_OK ){
        #ifdef TERMINAL
          Serial.println( http.getString() );
        #endif
        sucesso = true;
      }        
    }else{
      #ifdef TERMINAL
        Serial.printf("Erro na requisição: %s\n\r", http.errorToString(httpCode).c_str());
      #endif
    }  
    http.end();
  }else{ // Caso não consiga acesso ao wifi
    #ifdef TERMINAL
      Serial.printf( "Não foi possível conectar à rede!\n\r" );
    #endif
  }
  return sucesso;
}

float harversine( float lat1, float lon1, float lat2, float lon2 ){
  lat1 = deg2rad( lat1 ); lon1 = deg2rad( lon1 ); lat2 = deg2rad( lat2 ); lon2 = deg2rad( lon2 ); 

  float dlon = lon2 - lon1;
  float dlat = lat2 - lat1;
  
  float a = pow( sin(dlat/2.0), 2 ) + cos(lat1)*cos(lat2)*pow( sin(dlon/2.0), 2 );

  float c = 2.0 * asin( min( 1, sqrt(a)) );

  return 6368100 * c;
}

short pontoProx( void ){
  short j = 0; float dist = 0, distancia = harversine( posicaoAct.lat, posicaoAct.lon, comeco.lat, comeco.lon );
  for(short i = 0; i < qtdPontos; ++i){
    dist = harversine( posicaoAct.lat, posicaoAct.lon, pontos[i].pos.lat, pontos[i].pos.lon );
    if( distancia >= dist ) distancia = dist, j = i;
    else break;
  }
  #ifdef TERMINAL 
    Serial.printf( "\r\nPróximo ponto: %d %s\n\r", j, ( pontos[j].solicitado ) ? "solicitado!" : "" );
    Serial.printf( "\r\nDistância: " );Serial.print( distancia, 2 );Serial.printf( "m\n\r" );
  #elif defined( OPENGL )
    Serial.printf( "PRO:%03d\n", j );
  #endif
  return j;
}

void verificarPontoSolicitado( void ){
  short idPonto = pontoProx();
  if( pontos[idPonto].solicitado ) emAlerta = true;
  else emAlerta = false;
}

void verificacaoPeriodica( void ){
  long int tmp = (millis()-tempoIniViag);
  #ifdef TERMINAL
    limpaTela;
  #endif
  #ifdef TERMINAL
    Serial.printf( "Tempo gasto da viagem: " ); Serial.print( (tmp/1000.0f), 2 ); Serial.printf( "s\n\r" );
  #elif defined( OPENGL )
    Serial.printf( "TPA:%03ld\n", (long)(tmp/1000) );
  #endif
  if( tmp/1000.0 > interEntrePts ){
    #ifdef TERMINAL
      Serial.print( "Ônibus chegou ao fim do trajeto!\n\r" );
    #endif
    digitalWrite( LED_DA_ESP, LOW );
    while( true ) delay( 1 );
  }
  posicaoAct = {
    comeco.lat + ( velLatAn * (tmp/1000.0) ), 
    comeco.lon + ( velLonAn * (tmp/1000.0) )  
  };
  #ifdef TERMINAL
    Serial.printf( "\nPosição atual: " );Serial.print( posicaoAct.lat, 14 );Serial.print( "," );Serial.print( posicaoAct.lon, 14 );Serial.printf( "\n\r" );
    Serial.printf( "Quantidade de passageiros: %d\n\r", qtd );
    Serial.print( "Velocidade: ");Serial.print( velLinM, 2 );Serial.printf( "\n\r" );
  #elif defined( OPENGL )
    Serial.printf( "QTD:%03ld\n", qtd );
  #endif
}

void conectaServidor( void ){
  if(wifi.run() == WL_CONNECTED){ // Caso consiga conectar ao roteador
    HTTPClient http;
    String dados  = ENVIO;
           dados += "?id=" + String(_ID_BUS);
           dados += "&la=" + String(posicaoAct.lat, 10);
           dados += "&lo=" + String(posicaoAct.lon, 10);
           dados += "&lt=" + String(qtd);
           dados += "&vl=" + String(velLinM, 10);
           dados += "&ap=" + String(avisaPonto);
    
    http.begin( dados );

    #ifdef TERMINAL
      Serial.printf( "\r\nRequisitando servidor...\n\r" );
    #endif
    int httpCode = http.GET(); // Envia cabeçalho HTTP e retorna falha ou sucesso
  
    if( httpCode > 0 ){ // Caso consiga fazer o Request ao servidor.  
      if( httpCode == HTTP_CODE_OK ){
        filtraDados( http.getString() );
        avisaPonto = 0;
      }
        
    }else{
      #ifdef TERMINAL
        Serial.printf("\r\nErro na requisição: %s\n\r", http.errorToString(httpCode).c_str());
      #endif
    }
  
    http.end();
  }else{ // Caso não consiga acesso ao wifi
    #ifdef TERMINAL
      Serial.printf( "\r\nNão foi possível conectar à rede!\n\r" );
    #endif
  }
}

void filtraDados( String data ){
  #ifdef TERMINAL
    Serial.println( data );
  #endif
  if( data.indexOf( "OK" ) == -1 ) return;
  #ifdef TERMINAL
    Serial.printf( "Pontos com solicitações: " );
  #endif
  if( data.indexOf( "NONE" ) != -1 ){ 
    #ifdef TERMINAL
      Serial.printf( "\n\r" );
    #endif
    return;
  }
  for( short from = data.indexOf( "ID:" ); from != -1; from = data.indexOf( "ID:", from+6 ) ){
    short pontoAParar = data.substring(from+3, from+6).toInt();
    #ifdef TERMINAL
      Serial.printf( "%d ", pontoAParar );
    #elif defined( OPENGL )
      Serial.printf( "PSO:%03d\n", pontoAParar );
    #endif
    if( pontoAParar < qtdPontos )
      pontos[pontoAParar].solicitado = true;
  } 
  #ifdef TERMINAL
    Serial.printf( "\n\r" );
  #endif
}

void contaEntrada( void ){
  estadoEntradaAct = digitalRead( PUSH_ENTRADA );
  digitalWrite( LED_ENTRADA, estadoEntradaAct );
  // Verifica estado do botão de entrada
  if( estadoEntradaAct & !estadoEntrada ){ // Se o botão passou a ser pressionado
    estadoEntrada = HIGH;
    if( qtd < QTDMAX ) ++qtd;
  }else if( !estadoEntradaAct ){
    estadoEntrada = LOW;
  }
}

void contaSaida( void ){
  estadoSaidaAct = digitalRead( PUSH_SAIDA );
  digitalWrite( LED_SAIDA, estadoSaidaAct );
  // Verifica estado do botão de saída
  if( estadoSaidaAct & !estadoSaida ){ // Se o botão passou a ser pressionado
    estadoSaida = HIGH;
    if( qtd > 0 ) --qtd;
  }else if( !estadoSaidaAct ){
    estadoSaida = LOW;
  }
}

void piscaAlerta( void ){
  if( estadoAlerta1 ^ estadoAlerta2 ){
    digitalWrite( LED_ALERTA_1, estadoAlerta1 = !estadoAlerta1 );
    digitalWrite( LED_ALERTA_2, estadoAlerta2 = !estadoAlerta2 );
  }else{
    estadoAlerta1 = !estadoAlerta2;
  }
}























