<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Boston Gas</title>
        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="navbar">
                <img class= "logo-nav" src="{{ asset('images/Logo.png') }}" alt="logo" height="50px">

                <div class="btn">
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                        @endauth
                    </nav>  
                </div>  
            </div>
        </header> 
        
        <div class="container">
            <!-- Columna de la imagen -->
            <div class="left-column"></div>

            <!-- Columna de texto -->
            <div class="right-column">
                <div class="content">
                    <div class="informacion">
                        <h3 style="color: #2A4B89; margin-bottom: 10px;">¿Quiénes Somos?</h3>
                        <p>
                            En Boston Gas, nos dedicamos a ofrecer un servicio de calidad y confianza para satisfacer todas tus necesidades energéticas. Con años de experiencia en la industria, hemos consolidado nuestra reputación como un proveedor líder de combustibles en la región. Nuestra misión es garantizar que cada cliente reciba no solo el mejor producto, sino también un servicio excepcional y una experiencia agradable.
                            <br>
                            <br>
                            Visítanos y descubre por qué somos la elección preferida de la comunidad. En Boston Gas, tu satisfacción es nuestra prioridad.
                        </p>
                    </div>

                    <div class="ubicacion">
                        <div class="ubimg">
                            <img src="{{ asset('images/ubicacion.png') }}" alt="ubicacion" height="30px"> 
                        </div>
                        <div class="parrafo">
                            <p>Boston Gas</p>
                            <p>Calle Principal, Yucuaiquín, La Unión</p>
                        </div>
                    </div>

                    <div class="telefono">
                        <div class="telimg">
                            <img src="{{ asset('images/telefono.png') }}" alt="telefono" height="22px"> 
                        </div>
                        <div class="parrafo">
                            <p>2663-2780</p>
                        </div>
                    </div>

                    <div class="mail">
                        <div class="mailimg">
                            <img src="{{ asset('images/mail.png') }}" alt="telefono" height="24px"> 
                        </div>
                        <div class="parrafo">
                            <p>bostongas_station@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer style="width: 100%; height: 40px; display: flex; justify-content: center; align-items: center; background-color: white; overflow: hidden;">
            <p style="color: gray;">© 2024 - Boston Gas, todos los derechos reservados</p>
        </footer>          
    </body>
</html>