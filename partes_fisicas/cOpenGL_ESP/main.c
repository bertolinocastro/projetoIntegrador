#include <stdio.h>
#include <stdlib.h>
#include <GL/glew.h>
#include <GL/glut.h>
#include <string.h>
#include <unistd.h>

#define DEVICE "/dev/ttyUSB2"

#define TMP_TEXT "Tempo estimado de viagem: "
#define QTP_TEXT "Quantidade de pontos do trajeto: "
#define QTD_TEXT "No de passageiros: "
#define PSO_TEXT "Pontos solicitados: "
#define VEL_TEXT "Velocidade: "
#define PRO_TEXT "Ponto proximo: "
#define TPA_TEXT "Tempo real: "

#define COMP_MAX_STR_OPCOES 34

//#define GLUT_BITMAP_HELVETICA_18 GLUT_BITMAP_HELVETICA_10

typedef enum{
	false,
	true
} bool;

int int_p_string( int num , char **ptr );
bool rmSubstr( char *s, const char *toremove, size_t ult );

void limpa_tela( void );
void funcaoDisplay( void );
void funcaoTeclado( unsigned char key, int x, int y );
void leSerial( void );

void desenhaNaTela( void );
void escreveNaTela( void );

int tela_larg, tela_alt, passo;
int janela;

FILE * esp;

char bufferGlobal[64];

void limpaBuffer( void );

void atualizaTMP( int num );
void atualizaQTP( int num );
void atualizaQTD( int num );
void atualizaPSO( int num );
void atualizaVEL( int num );
void atualizaPRO( int num );
void atualizaTPA( int num );

char hotKeys[][5] = {
	"TMP:", // Tempo da viagem
	"QTP:", // Quantidade de pontos
	"QTD:", // Quantidade de passageiros
	"PSO:", // Pontos solicitados
	"VEL:", // Velocidade do veículo
	"PRO:", // Ponto próximo
	"TPA:"  // Tempo atual (Tempo da ESP)
};

void (* call[7]) ( int num ) = {
	atualizaTMP,
	atualizaQTP,
	atualizaQTD,
	atualizaPSO,
	atualizaVEL,
	atualizaPRO,
	atualizaTPA	
};

int tmp, qtp, qtd, vel, pro, tpa;
int (* variaveis[]) = {
	&tmp, &qtp, &qtd, &vel, &pro, &tpa
};
bool *pso = NULL;

GLfloat linhaTrajeto[6];
GLfloat *pontosTrajeto, *coresPontos;

int main(int argc, char **argv){
	GLenum erro;

	system( "stty -F "DEVICE" 115200" );
	system( "sudo chmod 777 "DEVICE );

	esp = fopen( DEVICE, "w+" );
	if( !esp ){printf("Falha ao abrir serial!\n"); exit(1);}
	
	glutInit( &argc, argv );

	glutInitDisplayMode( GLUT_DOUBLE | GLUT_RGB | GLUT_DEPTH );
	glutInitWindowSize( 640, 480 );

	janela = glutCreateWindow( "OnBus" );

	glutDisplayFunc( &funcaoDisplay );
	glutKeyboardFunc( &funcaoTeclado );

	glutFullScreen();

	tela_larg = glutGet( GLUT_SCREEN_WIDTH );
	tela_alt = glutGet( GLUT_SCREEN_HEIGHT );

	memcpy(
		linhaTrajeto,
		(GLfloat[6]){
			0.05f*tela_larg, 0.6f*tela_alt, 1.0f,
			0.95f*tela_larg, 0.6f*tela_alt, 1.0f,
		},
		6 * sizeof( GLfloat )
	);

	// Seta a grid ta tela a partir de 0 até os valores das variáveis
	glViewport( 0.0f, 0.0f, tela_larg, tela_alt );
	glMatrixMode( GL_PROJECTION );
	glLoadIdentity();
	glOrtho( 0, tela_larg, 0, tela_alt, -100, 100 );
	glMatrixMode( GL_MODELVIEW );
	glLoadIdentity();

	if( (erro = glewInit()) != GLEW_OK ){
		printf( "Erro ao acessar GLEW: %s\n", glewGetErrorString( erro ) );
		exit( 1 );
	}
/*
	atualizaQTP( 5 );
	atualizaTMP( 100 );
	atualizaTPA( 50 );
*/
	glutMainLoop();

	fclose( esp );

	return 0;
}

void funcaoDisplay( void ){
	char *substr; char val[4]; int valor; bool limpar = false;

	limpa_tela();

	leSerial();

	for(int i = 0; i < 7; ++i){
		if( (substr = strstr( bufferGlobal, hotKeys[i] )) ){
			memcpy( val,
				substr+4,
				4 * sizeof( char )
			);
			valor = atoi( val );
			call[i]( valor );
			limpar = true;
		}
	}

	desenhaNaTela();
	escreveNaTela();

	if( limpar ) limpaBuffer();

	glutSwapBuffers();
	
	glutPostRedisplay();
}

void leSerial( void ){
	fscanf( esp, " %7s", bufferGlobal );
	printf("%s\n", bufferGlobal );
}

void funcaoTeclado( unsigned char key, int x, int y ){
	if( key == 27 ) glutDestroyWindow( janela );
}

void limpa_tela( void ){
	glClear( GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT );
	glClearColor(0.0f, 0.0f, 0.0f, 1.0f);
}

/*
void leSerial( void ){
	char res;

	system( "stty -F "DEVICE" 115200" );

	FILE * esp = fopen( DEVICE, "w+" );
	if( !esp ){ printf("Falha ao abrir!\n"); exit(1); }

	while( 1 ){

		while( !feof( esp ) ){
			res = fgetc( esp );
			printf("%c",res );
		}

	}

	fclose( esp );
}
*/

void atualizaTMP( int num ){
	tmp = num;
}
void atualizaQTP( int num ){
	qtp = num;
	if( pso ) free( pso );
	if( pontosTrajeto ) free( pontosTrajeto );
	if( coresPontos ) free( coresPontos );
	pso = calloc( qtp, sizeof( *pso ) );
	pontosTrajeto = calloc( 12*qtp, sizeof( *pontosTrajeto ) );
	passo = (0.95f*tela_larg - 0.05f*tela_larg)/qtp;
	coresPontos = calloc( 12*qtp, sizeof( *coresPontos ) );
}
void atualizaQTD( int num ){
	qtd = num;
}
void atualizaPSO( int num ){
	if( num > qtp ) return;
	if( !pso[num] ) pso[num] = true;
}
void atualizaVEL( int num ){
	vel = num;
}
void atualizaPRO( int num ){
	pro = num;
}
void atualizaTPA( int num ){
	tpa = num;
}

bool rmSubstr( char *s, const char *toremove, size_t ult ){
  size_t tam = strlen( toremove ); bool achou = false;
  if( (s = strstr( s, toremove )) ) achou = true,
    memmove( s, s + tam + ult, 1 + strlen( s + tam + ult ) );
	return achou;
}

void desenhaNaTela( void ){
	int raio = 15, raioSeta = raio; float altPista = 0.6f*tela_alt, largPista = (0.05f*tela_larg + passo*0.5f);
	int raioI; float posSeta = ((float)tpa/tmp)*(tela_larg*0.95f - tela_larg*0.05f) + 0.05f*tela_larg;
	bool existe = false;

	/* Coords dos pontos */
	for( int i = 0; i < qtp; ++i ){
		raioI = ( pso[i] ) ? 2*raio : raio;
		for( int j = 0; j < 4; ++j ){

			int propX = ( j == 0 || j == 1 ) ? -raio : raio;
			int propY = ( j == 1 || j == 2 ) ? raioI : -raioI;

			pontosTrajeto[i*12 + j*3] 	  = largPista + passo*i + propX;
			pontosTrajeto[i*12 + j*3 + 1] = altPista + propY;
			pontosTrajeto[i*12 + j*3 + 2] = 0.0f;
		}
	}
	/* Cores dos pontos */
	for( int i = 0; i < qtp; ++i ){
		for( int j = 0; j < 4; ++j ){
			if( !pso[i] ){
				coresPontos[i*12 + j*3]     = 0.0f;
				coresPontos[i*12 + j*3 + 1] = 155.0f;
				coresPontos[i*12 + j*3 + 2] = 0.0f;
			}else{
				coresPontos[i*12 + j*3]     = 255.0f;
				coresPontos[i*12 + j*3 + 1] = 0.0f;
				coresPontos[i*12 + j*3 + 2] = 0.0f;
			}	
		}
	}

	/* Coords do ônibus */
	GLfloat coordOnib[9] = {
		posSeta     , altPista - raioSeta,       -0.1f,
		posSeta - 50, altPista - raioSeta - 150, -0.1f,
		posSeta + 50, altPista - raioSeta - 150, -0.1f
	};

	GLfloat corOnib[9];

	for( int i = 0; i < qtp; ++i ){
		if( pso[i] && i == pro ){ existe = true; break; }
	}
	if( !existe )
		memcpy(
			corOnib,
			(GLfloat[9]){
				0.0f, 0.0f, 255.0f,
				0.0f, 0.0f, 255.0f,
				0.0f, 0.0f, 255.0f
			},
			9 * sizeof( GLfloat )
		);
	else
		memcpy(
			corOnib,
			(GLfloat[9]){
				255.0f, 0.0f, 0.0f,
				255.0f, 0.0f, 0.0f,
				255.0f, 0.0f, 0.0f
			},
			9 * sizeof( GLfloat )
		);
	

	GLfloat coordProx[9] = {
		largPista + passo*pro,      altPista + raioSeta, -0.1f,
		largPista + passo*pro - 50, altPista + raioSeta + 50, -0.1f,
		largPista + passo*pro + 50, altPista + raioSeta + 50, -0.1f
	};


	glEnableClientState( GL_VERTEX_ARRAY );
	
	/* Desenho da linha do trajeto */
		glLineWidth( 5 );
		glVertexPointer( 3 , GL_FLOAT , 0 , linhaTrajeto );

		glDrawArrays( GL_LINES , 0 , 2 );
	
	/* Desenho dos pontos de ônibus do trajeto */
		glEnableClientState( GL_COLOR_ARRAY );

		glColorPointer( 3, GL_FLOAT, 0, coresPontos );
		glVertexPointer( 3, GL_FLOAT, 0, pontosTrajeto );
		glDrawArrays( GL_QUADS, 0, qtp*12 );

		glDisableClientState( GL_COLOR_ARRAY );
	
	/* Desenho da posição do ônibus */
		glEnableClientState( GL_COLOR_ARRAY );

		glColorPointer( 3, GL_FLOAT, 0, corOnib );
		glVertexPointer( 3, GL_FLOAT, 0, coordOnib );
		glDrawArrays( GL_TRIANGLES, 0, 9 );

		glDisableClientState( GL_COLOR_ARRAY );

	/* Desenho do ponto próximo */
		glVertexPointer( 3, GL_FLOAT, 0, coordProx );
		glDrawArrays( GL_TRIANGLES, 0, 9 );
		
	glDisableClientState( GL_VERTEX_ARRAY );

}

void escreveNaTela( void ){
	int i, j , tamValor; char *valor = NULL; int rasterX = 0.05f*tela_larg;
	#define printa_tab (glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , 9 ),glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , 9 ))
	
	const char texto[][COMP_MAX_STR_OPCOES] = {
		{TMP_TEXT},
		{QTP_TEXT},
		{QTD_TEXT},
		{VEL_TEXT},
		{PRO_TEXT},
		{TPA_TEXT},
		{PSO_TEXT}
	};

	glPushMatrix();
	glRasterPos2f( rasterX , 100 );

		for( j = 0; j < 6; ++j ){
			/* Printa o TMP e seu valor */
			for( i = 0 ; texto[j][i] != '\0' ; ++i ) glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , texto[j][i] );
			tamValor = int_p_string( *variaveis[j], &valor );
			for( i = tamValor - 1 ; i >= 0 ; --i ) glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , valor[i] );
			printa_tab;

			if( j == 2 ) glRasterPos2f( rasterX , 140 );
		}

		glRasterPos2f( rasterX , 180 );
		for( i = 0 ; texto[j][i] != '\0' ; ++i ) glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , texto[j][i] );
		
		for( j = 0; j < qtp; ++j ){
			if( pso[j] ){
				tamValor = int_p_string( j, &valor );
				for( i = tamValor - 1 ; i >= 0 ; --i ) glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18 , valor[i] );
			}
		}
		printa_tab;

	glPopMatrix();

	#undef printa_tab
}

int int_p_string( int num , char **ptr ){
	int i , count = 0;
	if( *ptr ) free( *ptr ) , *ptr = NULL;
	do{
		*ptr = (char *) realloc( *ptr , (count + 1) * sizeof( char ) );
		if( !(*ptr) ) printf("Erro com ponteiro. Arquivo: Int_P_String.\n"), exit(1);
		i = num % 10;
		*(*ptr+count) = i + '0';
		num /= 10;
		++count;
	}while( num );
	return count;
}

void limpaBuffer( void ){
	memcpy(
		bufferGlobal,
		(char [64]){'\0'},
		64 * sizeof( char )
	);
}
