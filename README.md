# Tabla 3x3

Este juego fue generado con [Symfony](https://symfony.com/) en su versión 5.1.3.

## Instalación

El código fuente puede ser descargado a través del repositorio de control de versiones [github](https://github.com/oreyne/game). 
## Development server

Asegúrese de instalar el fichero binario de symfony en este [enlace](https://symfony.com/download) y seleccione una 
de las tres opciones de ubicación de la variable de entorno `symfony` que le indica la ventana de comando.

También es recomendable que tenga instalado [composer](https://getcomposer.org/) y [yarn](https://classic.yarnpkg.com/en/docs/install/).

Procesa con los siguientes comandos, como se plantea en la [documentacón oficial](https://symfony.com/doc/current/setup.html#setting-up-an-existing-symfony-project):
1. cd projects/
2. git clone https://github.com/oreyne/game.git
3. cd game/
4. composer install

### Pasos finales

Haga una copia del fichero `.env` que se encuentra en la raíz del proyecto, nombrelo `.env.local` y modifique la linea 32, con las credenciales para acceder a su servidor de 
 base de datos, nombre de la base de datos y versión del servidor: `DATABASE_URL=mysql://USUARIO:CONTRASEÑA@127.0.0.1:3306/NOMBRE_DE_BASE_DE_DATOS?serverVersion=VERSION_DEL_SERVIDOR`. Para mayor información consulte la documentación oficial

Activamos el webpack para generar los css y js necesarios, con el comando `yarn encore dev --watch`.
Finalmente, ejecutamos el comando `symfony server:start`, el cual nos muestra una url a la cual podemos acceder para ver el juego en nuestro navegador

En caso de que al ejecutar este último comando, le solicitan la inserción de un usuario y contraseña; para ello es necesario que se cree una cuenta
en [Sf Connect](https://connect.symfony.com/login)

## Explicación del patrón MVC y lógica del juego

#### index.html.twig

Este fichero muestra la tabla con las posiciones que seleccionan los jugadores. En la tabla hay que señalar que cada celda
contiene un evento `onClick` para poder enviar los datos al servidor. Además, en este fichero, se cuenta con un enlace para poder reiniciar
el juego y una notificación para ver el estado del mismo.

#### app.js

Este fichero contiene la llamada `ajax` que envia los datos al servidor y de acuerdo a la respuesta
que reciba realizará las siguientes acciones:
1. Pintar en la celda seleccionada en la tabla una `X` ó una `O`.
2. Notificar el jugador que le toca jugar.
3. Notificar el jugador que ganó la partida ó no.
4. Notificar si ocurrió un error en el juego.

#### DefaultController.php

En esta controladora se encuentra la recepción de los datos 
enviados de la vista y parte de su procesamiento. Aqui encontramos dos funciones:
1. La función `index` se encargada de crear la entidad `Player` en caso de que no exista y 
inicializar el historial de jugadas
2. La función `playGame` chequea 4 acciones:
- Valida si existen jugadores en la base de datos
- Valida si ya hay un ganador
- Valida si el jugador que cliqueo en una celda de la tabla, lo hizo en una celda ya ocupada
- En caso de que ninguna de las previas validaciones muestra resultados negativos, procede a realizar las acciones necesarias
de la próxima jugada y verifica si hay o no un ganador en esa última jugada.

#### Winner.php

Este fichero es un servicio que contiene 4 funciones:
1. La función `calculateWinner` permite vericar si hay un ganador en la jugada realizada.
2. La función `convertToString` dado una cadena de texto, est se convierte a una lista de elementos.
3. La función `convertToArray` dado una lista de elementos, estos se convierten a una cadena de texto.
4. La función `resetGame` solicita el borrado de las jugadas en la base de datos.

#### Square.php
Este entidad representa el historial de las jugadas realizadas. Cuando se analiza el campo `value`
 en la base de datos, se muestra cuales fueron las casillas seleccionadas por los jugadores

#### Player.php
Esta entidad guarda el jugador al que le toca jugar asi como si existe o no un ganador.

#### SquareRepository.php
En este fichero se encuentra una consulta DQL para eliminar de la base de datos, todas las 
 filas excepto la que se pasa por parametro.