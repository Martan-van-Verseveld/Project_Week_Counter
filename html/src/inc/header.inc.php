<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        :root {
            /* Theme 1 */
            --primary: #DDE6ED;
            --secondary: #9DB2BF;
            --teriary: #526D82;
            --quaternary: #27374D;
            
            /* Theme 2 */
            /* --primary: #4D4D4D;
            --secondary: #B46060;
            --teriary: #FFBF9B;
            --quaternary: #FFF4E0; */
        }

        body {
            min-height: 100vh;
            background: var(--primary);
            display: flex;
            flex-direction: column;
        }

        main {
            margin: 0 1%;
            position: relative;
        }

        header, footer {
            background: var(--teriary); 
            color: var(--secondary);
            outline: .125rem solid var(--quaternary);

            font-family: monospace;
            text-align: center;

            padding: 1rem; 
            width: calc(100% - 2rem);
        }
        header { margin-bottom: 1rem; }
        footer { 
            margin-top: auto;
            bottom: 0;
            
            position: sticky;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }
        nav ul li {
            font-size: 1rem;
        }
        nav ul li#active {
            font-size: 1.5rem;
            font-weight: 600;
        }
    </style>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/libs/bootstrap/bootstrap_icons.css">
    <script src="/js/functions.js"></script>
    <title><?= ucfirst($_GET['page']) ?></title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php

                    $pages = array_diff(scandir("{$_SERVER['DOCUMENT_ROOT']}/pages"), ['.', '..']);

                    foreach ($pages as $page) {
                        $page = basename($page, '.php');
                        echo "<li". (($page == $_GET['page']) ? " id='active'" : "") ."><a href='/index.php?page=$page'>". ucfirst($page) ."</a></li>";
                    }

                ?>
            </ul>
        </nav>
        <!-- <h1><a href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/index.php?page=home' ?>"><?= ucfirst($_GET['page']) ?> page</a></h1> -->
    </header>