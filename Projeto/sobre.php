<!-- Página Sobre -->
<?php

$template = array(
	'titulo' => 'Sobre &mdash; GM Supermercado',
	'styles' => ['static/css/sobre.css']
);
include __DIR__ . '/src/template/header.php';

?>
    <div class="container">
        <h2 class="title">Nossa Missão</h2>
        <p>Comprometidos em oferecer produtos de excelência e um atendimento diferenciado, nosso objetivo é superar as expectativas, garantindo a confiança e satisfação de nossos clientes e colaboradores.</p>
        <h2 class="title">Nossos Valores</h2>
        <p>Valorizamos a integridade, cultivamos o respeito por nossos clientes, promovemos a inovação constante e mantemos um compromisso inabalável com a qualidade em tudo o que fazemos.</p>
        <h2 class="title">Nossa História</h2>
        <p>Desde a sua fundação, o GM Mercado nasceu com o propósito de transformar o mercado local, oferecendo soluções que vão além das expectativas. Ao longo dos anos, temos crescido de maneira sólida, graças ao nosso compromisso com a qualidade, a inovação e o respeito por cada cliente. Essa dedicação nos permitiu conquistar não apenas a confiança, mas também a fidelidade de nossos clientes e parceiros, fortalecendo nossa posição como referência no setor.</p>
        <h2 class="title">Nossa Equipe</h2>
        <p>Um time de profissionais dedicados, preparados para atender todas as necessidades dos nossos clientes.</p>
        <img src="static/img/gm (2).jpg" alt="Empresa" class="footer-image">
    </div>

<?php include __DIR__ . '/src/template/footer.php'; ?>