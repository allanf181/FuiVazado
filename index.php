<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>FuiVazado!</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/css/pikaday.min.css">
    <!-- o Google ads era pro caso de os custos do site ficarem muito altos e terem poucas doações, está desativada a exibição de anuncios no site até o momento -->
    <script data-ad-client="ca-pub-1891500891040476" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .g-recaptcha {
            display: inline-block;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-white portfolio-navbar gradient"
     style="background: linear-gradient(145deg, black 0%, rgb(109,109,109) 100%);">
    <div class="container"><a class="navbar-brand logo" href="/">FuiVazado!</a>
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navbarNav"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link active" href="/">CPF</a></li>
                <li class="nav-item"><a class="nav-link active" href="https://www.syhunt.com/pt/leakcheck/" target="_blank">CNPJ</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="/infos/">Informações sobre os dados</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="page lanidng-page">
    <section class="portfolio-block block-intro">
<!--        <div role="alert" class="alert alert-success"><span><strong>Migração no site</strong><br />A migração do site foi concluída. Continuaremos monitorando a situação para que o site continue online.</span></div>-->
<!--        <div class="alert alert-info" role="alert"><span><strong>Aviso de phishing</strong><br />O aviso é um falso-positivo provavelmente devido ao excesso de erros 5xx causados pela grande quantidade de acessos que o servidor não comportava, já foi solicitada a remoção do aviso.</span></div>-->
        <?php
        function validaCPF($cpf)
        {

            $cpf = preg_replace('/[^0-9]/is', '', $cpf);

            if (strlen($cpf) !== 11) {
                return false;
            }

            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }

            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;

        }

        function formatName($name)
        {
            $words = explode(" ", $name);
            $formated = $words[0];
            unset($words[0]);
            foreach ($words as $w) {
                if (strlen($w) > 3) {
                    $formated .= " " . $w[0] . ".";
                } else {
                    $formated .= " " . $w;
                }
            }

            return $formated;
        }

        function Mask($mask, $str)
        {

            $str = str_replace(" ", "", $str);

            for ($i = 0; $i < strlen($str); $i++) {
                $mask[strpos($mask, "#")] = $str[$i];
            }

            return $mask;

        }

        function formatInfo($info)
        {
            if ($info == "×") {
                return "❌";
            } else {
                return "✅";
            }
        }

        function validateCaptcha($captcha)
        {
            $secretKey = 'CAPTCHA-SECRET-KEY';
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
            $response = file_get_contents($url);
            $responseJson = json_decode($response);
            return $responseJson->success;
        }

        ?>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>
            <div class="container">
                <div class="about-me">
                    <p>Descubra aqui se seus dados estão no&nbsp;<a
                                href="https://tecnoblog.net/404838/exclusivo-vazamento-que-expos-220-milhoes-de-brasileiros-e-pior-do-que-se-pensava/"
                                target="_blank">vazamento de 220M&nbsp; de CPFs</a>.<br></p>
                </div>
            </div>
            <form method="post">
                <div class="form-group text-left"><label for="subject">CPF (Somente números, 11 dígitos)</label><input
                            class="form-control" type="text" required="" pattern="[0-9]{11}" minlength="11"
                            maxlength="11" placeholder="01234567890" name="cpf"></div>
                <div class="form-group text-left"><label for="email">Data de nascimento (dd/mm/aaaa)</label><input
                            class="form-control" type="text"
                            pattern="(?:((?:0[1-9]|1[0-9]|2[0-9])\/(?:0[1-9]|1[0-2])|(?:30)\/(?!02)(?:0[1-9]|1[0-2])|31\/(?:0[13578]|1[02]))\/(?:18|19|20)[0-9]{2})"
                            required="" minlength="10" maxlength="10" placeholder="01/01/1999" name="dt"></div>
                <div class="form-group align-content-center">
                    <div class="g-recaptcha" data-sitekey="6LePhEAaAAAAAJTL-WVe_QWM4cHSJIojwM_E1I7f"></div>
                </div>
                <button class="btn btn-primary" type="submit">Checar</button>
            </form>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf']) && isset($_POST['dt']) && isset($_POST['g-recaptcha-response'])) : ?>
            <?php if (validaCPF($_POST["cpf"])): ?>
                <?php if (validateCaptcha($_POST['g-recaptcha-response'])): ?>
                    <?php

                    $servername = "localhost";
                    $username = "username";
                    $password = "password";
                    $dbname = "DB";

                    $conn = new MongoDB\Driver\Manager("mongodb://${username}:${password}@${servername}/${dbname}");
                    $cpf = intval($_POST["cpf"]);
                    $dt = $_POST["dt"];
                    $filter = ['cpf' => $cpf];
                    $options = array(
                        'limit' => 1
                    );
                    $query = new MongoDB\Driver\Query($filter, $options);
                    $result = $conn->executeQuery('DB.COLLECTION', $query)->toArray()[0];
                    $found = false;
                    if ($result!==null) {
                        $found = true;
                    }
                    $row = (array) $result;
                    ?>
                    <?php if ($found): ?>
                        <div class="container">
                            <div class="about-me">
                                <p>Sim, seus dados vazaram.</p>
                            </div>
                        </div>
                        <?php if ($row['dt'] == $dt || $row['dt'] == "N/D"): ?>
                            <div class="container">
                                <div class="skills portfolio-info-card">
                                    <h2>Nome (abreviado por segurança)</h2>
                                    <div class="alert alert-warning" role="alert">
                                        <span><?php echo formatName($row['nm']) ?></span>
                                    </div>
                                    <h2>CPF</h2>
                                    <div class="alert alert-warning" role="alert">
                                        <span><?php echo Mask("###.###.###-##", sprintf('%011d', $row['cpf'])) ?></span>
                                    </div>
                                    <h2>Sexo / Gênero</h2>
                                    <div class="alert alert-warning" role="alert">
                                        <span><?php echo $row['sexo'] ?></span>
                                    </div>
                                    <h2>Data de nascimento</h2>
                                    <div class="alert alert-warning" role="alert"><span><?php echo $row['dt'] ?></span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Dado vazado</th>
                                                <th>Vazou <br />(✅ se sim, ❌se não)<br /></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Básico</td>
                                                <td><?php echo formatInfo($row['01']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><?php echo formatInfo($row['02']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Telefone</td>
                                                <td><?php echo formatInfo($row['03']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Endereço</td>
                                                <td><?php echo formatInfo($row['04']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Mosaic</td>
                                                <td><?php echo formatInfo($row['05']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Ocupação</td>
                                                <td><?php echo formatInfo($row['06']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Score de Crédito</td>
                                                <td><?php echo formatInfo($row['07']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Registro Geral</td>
                                                <td><?php echo formatInfo($row['08']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Título de Eleitor</td>
                                                <td><?php echo formatInfo($row['09']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Escolaridade</td>
                                                <td><?php echo formatInfo($row['10']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Empresarial</td>
                                                <td><?php echo formatInfo($row['11']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Receita Federal</td>
                                                <td><?php echo formatInfo($row['12']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Classe Social</td>
                                                <td><?php echo formatInfo($row['13']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Estado Civil</td>
                                                <td><?php echo formatInfo($row['14']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Emprego</td>
                                                <td><?php echo formatInfo($row['15']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Afinidade</td>
                                                <td><?php echo formatInfo($row['16']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Modelo Analítico</td>
                                                <td><?php echo formatInfo($row['17']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Poder Aquisitivo</td>
                                                <td><?php echo formatInfo($row['18']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Fotos de Rostos</td>
                                                <td><?php echo formatInfo($row['19']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Servidores Público</td>
                                                <td><?php echo formatInfo($row['20']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cheques sem Fundos</td>
                                                <td><?php echo formatInfo($row['21']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Devedores</td>
                                                <td><?php echo formatInfo($row['22']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Bolsa Família</td>
                                                <td><?php echo formatInfo($row['23']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Universitários</td>
                                                <td><?php echo formatInfo($row['24']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Conselhos</td>
                                                <td><?php echo formatInfo($row['25']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Domicílios</td>
                                                <td><?php echo formatInfo($row['26']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vínculos</td>
                                                <td><?php echo formatInfo($row['27']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>LinkedIn</td>
                                                <td><?php echo formatInfo($row['28']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Salário</td>
                                                <td><?php echo formatInfo($row['29']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Renda</td>
                                                <td><?php echo formatInfo($row['30']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Óbitos</td>
                                                <td><?php echo formatInfo($row['31']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>IRPF</td>
                                                <td><?php echo formatInfo($row['32']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>INSS</td>
                                                <td><?php echo formatInfo($row['33']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>FGTS</td>
                                                <td><?php echo formatInfo($row['34']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>CNS</td>
                                                <td><?php echo formatInfo($row['35']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>NIS</td>
                                                <td><?php echo formatInfo($row['36']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>PIS</td>
                                                <td><?php echo formatInfo($row['37']) ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="container">
                                <div role="alert" class="alert alert-danger">
                                    <span><strong>Data de nascimento incorreta. </strong>Por motivos de segurança os detalhes do vazamento só podem ser mostrados com a data de nascimento correta.<br/></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="container">
                            <div class="about-me">
                                <p>CPF não encontrado, provavelmente seus dados não vazaram.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="container">
                        <div role="alert" class="alert alert-danger"><span><strong>Captcha inválido. </strong>Por favor resolva o captcha.<br/></span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="container">
                    <div role="alert" class="alert alert-danger"><span><strong>CPF inválido. </strong>Digite o CPF corretamente.<br/></span>
                    </div>
                </div>
            <?php endif; ?>
            <div class="container"><a class="btn btn-primary" role="button" style="margin-top: 35px;" href="/">Realizar
                    nova busca</a></div>
        <?php else: ?>
            <div class="container">
                <div role="alert" class="alert alert-danger">
                    <span><strong>ERRO. </strong>Parâmetros incorretos.<br/></span>
                </div>
            </div>
            <div class="container"><a class="btn btn-primary" role="button" style="margin-top: 35px;" href="/">Realizar
                    nova busca</a></div>
        <?php endif; ?>
    </section>
    <section class="portfolio-block photography"></section>
    <section class="portfolio-block skills">
        <div class="container">
            <div class="heading">
                <h2>Sobre o vazamento</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card special-skill-item border-0">
                        <div class="card-header bg-transparent border-0"><i class="icon ion-person"></i></div>
                        <div class="card-body">
                            <h3 class="card-title">223.739.215 de CPFs<br></h3>
                            <p class="card-text">Esse é o número de dados de pessoas físicas que foram vazados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card special-skill-item border-0">
                        <div class="card-header bg-transparent border-0"><i class="icon ion-ios-briefcase"></i></div>
                        <div class="card-body">
                            <h3 class="card-title">40.183.784 de CNPJs<br></h3>
                            <p class="card-text">Quantidade de dados de pessoas jurídicas vazados. Ambos datados de
                                08/2019.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card special-skill-item border-0">
                        <div class="card-header bg-transparent border-0"><i class="icon ion-pie-graph"></i></div>
                        <div class="card-body">
                            <h3 class="card-title">Serasa Experian<br></h3>
                            <p class="card-text">Essa é a possível origem do vazamento. A empresa nega. ANPD, Senacon e
                                Procon-SP já notificaram a empresa para que ela&nbsp;&nbsp;explique seu possível
                                envolvimento.<br><br></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<section class="portfolio-block website gradient">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-5 offset-lg-1 text">
                <h3>Sobre esse site</h3>
                <p>Esse site tem a exclusiva finalidade de servir de consulta para que todos afetados pelo vazamento
                    saibam se seus dados foram vazados e quais foram, os únicos dados armazenados&nbsp; são CPF, Nome
                    Completo, Data de nascimento, Sexo / Gênero e uma lista de 37 itens onde somente informam se o dado
                    vazou ou não, esse site não possui nenhum outro dado sobre nenhum desses 37 itens.<br><br><strong>Esses
                        dados foram obtidos em um fórum online, conforme citato na noticia do Tecnoblog.</strong><br><br>
                </p>
            </div>
            <div class="col-md-12 col-lg-5">
                <div class="portfolio-laptop-mockup">
                    <div class="screen">
                        <div class="screen-content"
                             style="background: url(&quot;/assets/img/Tempclipboard-image.jpg&quot;);background-size: cover;"></div>
                    </div>
                    <div class="keyboard"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="page-footer">
    <div class="container">
        <h2>Ajude a manter esse site no ar</h2>
        <p>Manter essa quantidade de dados num banco de dados de rápida consulta não é barato.<br>Contribua da forma que puder para que esse site continue funcionando e todos possam saber quais dados foram vazados.</p>
        <ul class="list-unstyled">
            <li><a href="https://www.paypal.com/donate?hosted_button_id=WP6878F88R8DA" target="_blank">Doar com PayPal</a></li>
            <li><a href="https://app.picpay.com/user/armelin1" target="_blank">Doar com PicPay</a></li>
            <li><a href="https://nubank.com.br/pagar/zaqp/I6Z8QbZslP" target="_blank">Doar via PIX</a></li>
        </ul>
        <div></div>
        <section></section>
    </div>
    <div class="container">
        <div class="links"></div>
        <p><br>Desenvolvido por Allan Fernando<br></p>
        <div class="social-icons">
            <p><a href="https://github.com/allanf181/" target="_blank"><i class="icon ion-social-github"></i></a><a
                        href="https://linkedin.com/in/allan-fernando/" target="_blank"><i
                            class="icon ion-social-linkedin"></i></a><a href="https://t.me/allanf181" target="_blank"><i class="icon ion-paper-airplane"></i></a><a href="mailto: allanfernando@pm.me"><i
                            class="icon ion-android-mail"></i></a></p>
        </div>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/pikaday.min.js"></script>
<script src="/assets/js/script.min.js"></script>
</body>

</html>
