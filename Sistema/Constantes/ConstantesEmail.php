<?php

define("EMAIL_CABECERA", '<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <style type="text/css">
            header, section{ display: block;}
            body {
                    font-family: Arial, Helvetica, Verdana;
                    font-size: 1em;
                    max-width: 95%;
                    border-left: 2px blue solid;
                    border-right: 2px blue solid;
                    margin: 5px auto;
                    position:relative; 
            }
            header,section#contenedor{
                    background-color: #fff;
                    margin: 1em auto;
                    max-width: 960px;
                    min-width: 470px;
                    padding: .25em;
            }
            section#cabecera, section#contenedor{ 
                    width: 90%;
                    margin: 1em auto;
            }
            section#cabecera h3{
                    margin: .25em auto;
                    text-align: center;
            }    
            section#saludo{
                    width: 80%;
                    margin: 1em auto;
                    font-size: 1.5em;
                    color: black;
            }
            h1{
                    font-family:Arial, Helvetica, Verdana;
                    font-size: 4.5em;
                    text-align: center;
                    color:#FF5917;
                    text-shadow: 5px 5px 5px rgba(000,000,000,0.7);
            }
                .especial{
                color: #FF5917;
                font-size: 1.5em;
                }
        
        </style>
        </head>
        <header>
        <section id="cabecera">
        <h1>Te lo cambio</h1>
        <h3>Miles de personas compartiendo te están en esperando.</h3>
        </section>
        </header>
        <body>
        <section id="contenedor">'
        );
define("EMAIL_FOOTER", '</section><footer></footer></body></html>');


define("EMAIL_FROM", "administracion@ichangeityou.com");
define("EMAIL_FROM_NAME", "Administración de Te lo cambio.");
define("EMAIL_SUBJECT_REGISTER", "Email de TE LO CAMBIO");




