<main class="legal-page">
  <div class="container">
    <a href="/<?php echo $lang; ?>" class="legal-back">← <?php echo htmlspecialchars($t['footer.nav.home'] ?? 'Home'); ?></a>

    <?php if ($lang === 'en'): ?>
    <header class="legal-header">
      <p class="section-label">Legal</p>
      <h1>Cookie Policy</h1>
      <p class="legal-meta">Last updated: April 2026</p>
    </header>
    <div class="legal-body">
      <p>This Policy explains how the Atlantic Crown Coffee website uses browser storage and similar technologies.</p>
      <h2>1. We Do Not Use Tracking Cookies</h2>
      <p>This website <strong>does not use tracking, analytics or advertising cookies</strong>. We do not install any cookies in your browser for monitoring or marketing purposes.</p>
      <h2>2. Local Storage (localStorage)</h2>
      <p>We use localStorage — distinct from HTTP cookies — for:</p>
      <ul>
        <li><strong>Language preference</strong> (key: <code>lang</code>) — stores your selected language (PT, EN or FR).</li>
        <li><strong>Privacy notice</strong> (key: <code>privacy_notice_accepted</code>) — stores whether you have acknowledged the notice.</li>
      </ul>
      <p>This data is stored exclusively on your device and is never sent to our servers.</p>
      <h2>3. How to Clear Local Storage</h2>
      <p>You can clear localStorage at any time via your browser settings (Privacy → Clear site data).</p>
      <h2>4. Changes</h2>
      <p>We reserve the right to update this policy. The date of the last update is at the top.</p>
      <h2>5. Contact</h2>
      <p><a href="mailto:geral@atlanticcrowncoffee.com">geral@atlanticcrowncoffee.com</a></p>
    </div>

    <?php elseif ($lang === 'pt'): ?>
    <header class="legal-header">
      <p class="section-label">Legal</p>
      <h1>Política de Cookies</h1>
      <p class="legal-meta">Última atualização: Abril 2026</p>
    </header>
    <div class="legal-body">
      <p>Esta Política explica como o website da Atlantic Crown Coffee utiliza o armazenamento do browser e tecnologias similares.</p>
      <h2>1. Não Utilizamos Cookies de Rastreamento</h2>
      <p>Este website <strong>não utiliza cookies de rastreamento, analíticos ou publicitários</strong>. Não instalamos nenhum cookie no seu browser para fins de monitorização ou marketing.</p>
      <h2>2. Armazenamento Local (localStorage)</h2>
      <p>Utilizamos localStorage — tecnologia diferente dos cookies HTTP — para:</p>
      <ul>
        <li><strong>Preferência de idioma</strong> (chave: <code>lang</code>) — guarda o idioma selecionado (PT, EN ou FR).</li>
        <li><strong>Aviso de privacidade</strong> (chave: <code>privacy_notice_accepted</code>) — guarda se já aceitou o aviso.</li>
      </ul>
      <p>Estes dados ficam exclusivamente no seu dispositivo e nunca são enviados para os nossos servidores.</p>
      <h2>3. Como Limpar o Armazenamento Local</h2>
      <p>Pode limpar o localStorage a qualquer momento nas definições do seu browser (Privacidade → Limpar dados do site).</p>
      <h2>4. Alterações</h2>
      <p>Reservamo-nos o direito de atualizar esta política. A data da última atualização encontra-se no topo.</p>
      <h2>5. Contacto</h2>
      <p><a href="mailto:geral@atlanticcrowncoffee.com">geral@atlanticcrowncoffee.com</a></p>
    </div>

    <?php elseif ($lang === 'fr'): ?>
    <header class="legal-header">
      <p class="section-label">Légal</p>
      <h1>Politique de Cookies</h1>
      <p class="legal-meta">Dernière mise à jour : Avril 2026</p>
    </header>
    <div class="legal-body">
      <p>Cette Politique explique comment le site Atlantic Crown Coffee utilise le stockage du navigateur et les technologies similaires.</p>
      <h2>1. Nous N'utilisons Pas de Cookies de Traçage</h2>
      <p>Ce site <strong>n'utilise pas de cookies de traçage, d'analyse ou de publicité</strong>. Nous n'installons aucun cookie dans votre navigateur.</p>
      <h2>2. Stockage Local (localStorage)</h2>
      <p>Nous utilisons localStorage — distinct des cookies HTTP — pour :</p>
      <ul>
        <li><strong>Préférence de langue</strong> (clé : <code>lang</code>) — enregistre la langue sélectionnée (PT, EN ou FR).</li>
        <li><strong>Avis de confidentialité</strong> (clé : <code>privacy_notice_accepted</code>) — enregistre si vous avez accepté l'avis.</li>
      </ul>
      <p>Ces données sont stockées exclusivement sur votre appareil et ne sont jamais envoyées à nos serveurs.</p>
      <h2>3. Comment Effacer le Stockage Local</h2>
      <p>Vous pouvez effacer le localStorage via les paramètres de votre navigateur (Confidentialité → Effacer les données du site).</p>
      <h2>4. Modifications</h2>
      <p>Nous nous réservons le droit de mettre à jour cette politique. La date figure en haut.</p>
      <h2>5. Contact</h2>
      <p><a href="mailto:geral@atlanticcrowncoffee.com">geral@atlanticcrowncoffee.com</a></p>
    </div>

    <?php endif; ?>
  </div>
</main>
