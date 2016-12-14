#define DELAY 500

#define LRD_IN_1 A4
#define LRD_IN_2 A5

#define LIM_PERCEP 62

void printa_serial( int value );
int stateIn1, stateIn2;
int prevStateIn1 = 0, prevStateIn2 = 0;
int qtd = 0;
unsigned long tempo;

void setup( void ){
  Serial.begin(9600);
  prevStateIn1 = analogRead(LRD_IN_1);
  prevStateIn2 = analogRead(LRD_IN_2);
}

void loop( void ){
  stateIn1 = analogRead(LRD_IN_1);
  stateIn2 = analogRead(LRD_IN_2);

  Serial.print("A4: "); Serial.println(stateIn1);
  Serial.print("A5: "); Serial.println(stateIn2);
  Serial.println();

  if( prevStateIn1 < LIM_PERCEP ) // LRD1 prev. apagado
    if( prevStateIn2 < LIM_PERCEP ) // LRD2 prev. apagado
      if( stateIn1 > LIM_PERCEP ) printa_serial("Entrada",++qtd); // LRD1 atualm. aceso -- simboliza passagem

  delay(2*DELAY);
  prevStateIn1 = stateIn1;
  prevStateIn2 = stateIn2;
}

void printa_serial( char pal[], int value ){
  if( millis() < tempo + DELAY ) return;
  Serial.println("------------------");
  Serial.print(pal+' ');
  Serial.print(value);
  Serial.println();
  tempo = millis();
}

