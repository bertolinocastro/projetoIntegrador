#include <Arduino.h>

#define CODIGO_PONTO 13 // Identificador do ponto de ônibus (ordem do ponto em relação ao itinerario da linha)
#define QTD_LINHAS 1   // Quantidade de linhas associadas ao ponto

#define LED_VERM 11    // Porta Digital com PWM
#define LED_AMAR 10    // Porta Digital com PWM
#define LED_VERD  9    // Porta Digital com PWM

#define TEMPO_MINIMO 15 // Tempo (s) mínimo para emitir luz da chegada do ônibus

int tempoDeAviso[QTD_LINHAS] = {0};

void imprimeID( void );
void leSerial( void );
void iluminaLed( void );

float tempo_total      = 0;
long  tempo_imprime_id = 0;
long  tempo_leSerial   = 0;

void setup() {
  Serial.begin(9600);
  pinMode( LED_VERD, OUTPUT );
  pinMode( LED_VERM, OUTPUT );
  pinMode( LED_AMAR, OUTPUT );
  pinMode(        7, OUTPUT );
  digitalWrite( 7, LOW );
  imprimeID();
  delay( 2000 );
}

void loop() {
  tempo_total = micros()/1000000.0f;
  //if( tempo_total > tempo_leSerial + 5 ){
    leSerial();
    //tempo_leSerial = tempo_total; 
  //}
  iluminaLed();
  delay(1);
}

void imprimeID( void ){
  uint8_t i = 4;
  while( --i ) Serial.flush(), delay( 20 );
  Serial.print( CODIGO_PONTO, DEC );
}

void leSerial( void ){
  String dado = ""; int linha, from, fromT, tempo, tp;
  while( Serial.available() > 0 ){
    dado += (char) Serial.read();
    delay(1);
  }
  if( dado.indexOf( "OK" ) == -1 ) return;
  uint8_t i = 4;
  while( --i ) Serial.flush(), delay( 20 );
  Serial.println( dado );
  for( from = dado.indexOf( "ID:" ); from != -1; from = dado.indexOf( "ID:", from + 12 ) ){
    linha = dado.substring( from + 3, from + 6 ).toInt();
    fromT = dado.indexOf( "TP:", from );
    tp = dado.substring( fromT + 3, fromT + 6 ).toInt();
    //Serial.println( "Dado informado: " + String( linha, DEC ) + " " + String( tp, DEC ) );
    tempoDeAviso[linha-1] = tp;
  }
}

void iluminaLed( void ){
  for( int i = 0; i < QTD_LINHAS; ++i ){
    float percent = (tempoDeAviso[i]-tempo_total) / (float) TEMPO_MINIMO;
    if( percent < 1 && percent > 0 ){
      analogWrite( LED_VERD, 255 - min( 255, max( 0, 255*((percent-2/3.0f)/(1/3.0f)))) ); // % do 3 segmento de um inteiro. A iluminação é data por quanto menor for o valor.
      analogWrite( LED_AMAR, 255 - min( 255, max( 0, 255*((percent-1/3.0f)/(1/3.0f)))) ); // % do 2 segmento de um inteiro. A iluminação é data por quanto menor for o valor.
      analogWrite( LED_VERM, 255 - min( 255, max( 0, 255*((percent-0/3.0f)/(1/3.0f)))) ); // % do 1 segmento de um inteiro. A iluminação é data por quanto menor for o valor.
    }else{
      analogWrite( LED_VERM, 0 );
      analogWrite( LED_VERD, 0 );
      analogWrite( LED_AMAR, 0 );
    }
  }
}





















