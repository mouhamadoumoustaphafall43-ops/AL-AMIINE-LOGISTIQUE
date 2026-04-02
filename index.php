<?php
require_once 'includes/config.php';

// Fetch dynamic data
$produits_fer = $pdo->query("SELECT * FROM produits WHERE categorie='fer' AND actif=1 LIMIT 6")->fetchAll();
$produits_bois = $pdo->query("SELECT * FROM produits WHERE categorie='bois' AND actif=1 LIMIT 6")->fetchAll();
$temoignages = $pdo->query("SELECT * FROM temoignages WHERE approuve=1 ORDER BY date_creation DESC LIMIT 6")->fetchAll();
$galerie = $pdo->query("SELECT * FROM galerie WHERE actif=1 ORDER BY date_creation DESC LIMIT 8")->fetchAll();

// Handle devis form
$msg_success = $msg_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_devis'])) {
    $nom = sanitize($_POST['nom'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $tel = sanitize($_POST['telephone'] ?? '');
    $service = sanitize($_POST['service'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if ($nom && $email && $tel && $message) {
        $stmt = $pdo->prepare("INSERT INTO devis (nom,email,telephone,service,message) VALUES (?,?,?,?,?)");
        $stmt->execute([$nom, $email, $tel, $service, $message]);
        $msg_success = "Votre demande de devis a bien été envoyée ! Nous vous contactons sous 24h.";
    } else {
        $msg_error = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Handle témoignage form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_temoignage'])) {
    $t_nom = sanitize($_POST['t_nom'] ?? '');
    $t_poste = sanitize($_POST['t_poste'] ?? '');
    $t_msg = sanitize($_POST['t_message'] ?? '');
    $t_note = intval($_POST['t_note'] ?? 5);
    if ($t_nom && $t_msg) {
        $stmt = $pdo->prepare("INSERT INTO temoignages (nom,poste,message,note) VALUES (?,?,?,?)");
        $stmt->execute([$t_nom, $t_poste, $t_msg, $t_note]);
        $msg_success_t = "Merci pour votre témoignage ! Il sera publié après modération.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AL AMIINE LOGISTIQUE – Transport & Matériaux de Construction</title>
<meta name="description" content="AL AMIINE LOGISTIQUE : Transport de matériel de construction, vente de fer et bois. Qualité, fiabilité et service à Dakar.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --noir: #0a0a0a;
  --blanc: #fafafa;
  --gris-clair: #f4f4f2;
  --gris: #e8e8e4;
  --gris-med: #a0a09a;
  --or: #c9a84c;
  --or-clair: #e8d08a;
  --rouge: #c0392b;
  --bleu-acier: #1a2744;
  --text: #2a2a2a;
  --text-light: #6b6b65;
  --radius: 2px;
  --shadow: 0 4px 40px rgba(0,0,0,0.08);
  --shadow-lg: 0 20px 80px rgba(0,0,0,0.15);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
  font-family: 'DM Sans', sans-serif;
  color: var(--text);
  background: var(--blanc);
  overflow-x: hidden;
  line-height: 1.6;
}

/* ── NAVBAR ── */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  padding: 0 5%;
  display: flex; align-items: center; justify-content: space-between;
  height: 70px;
  background: rgba(250,250,250,0.92);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--gris);
  transition: all 0.3s;
}
nav.scrolled {
  height: 60px;
  box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}
.nav-logo {
  display: flex; align-items: center; gap: 12px;
  text-decoration: none;
}
.nav-logo-icon {
  width: 40px; height: 40px;
  background: var(--bleu-acier);
  display: flex; align-items: center; justify-content: center;
  clip-path: polygon(0 0, 85% 0, 100% 50%, 85% 100%, 0 100%);
}
.nav-logo-icon i { color: var(--or); font-size: 16px; }
.nav-logo-text { line-height: 1.1; }
.nav-logo-text .brand { font-family: 'Bebas Neue', sans-serif; font-size: 18px; color: var(--bleu-acier); letter-spacing: 1px; }
.nav-logo-text .tagline { font-size: 9px; color: var(--gris-med); text-transform: uppercase; letter-spacing: 2px; }
.nav-links { display: flex; align-items: center; gap: 32px; list-style: none; }
.nav-links a { text-decoration: none; font-size: 13px; font-weight: 500; color: var(--text); letter-spacing: 0.5px; position: relative; transition: color 0.2s; }
.nav-links a::after { content: ''; position: absolute; bottom: -3px; left: 0; width: 0; height: 1px; background: var(--or); transition: width 0.3s; }
.nav-links a:hover { color: var(--bleu-acier); }
.nav-links a:hover::after { width: 100%; }
.nav-cta {
  padding: 9px 22px;
  background: var(--bleu-acier); color: var(--blanc) !important;
  font-size: 12px !important; font-weight: 600 !important;
  text-transform: uppercase; letter-spacing: 1px;
  text-decoration: none;
  transition: all 0.2s !important;
  clip-path: polygon(0 0, 92% 0, 100% 50%, 92% 100%, 0 100%);
}
.nav-cta:hover { background: var(--or) !important; color: var(--noir) !important; }
.nav-cta::after { display: none !important; }
.hamburger { display: none; background: none; border: none; cursor: pointer; flex-direction: column; gap: 5px; }
.hamburger span { display: block; width: 24px; height: 2px; background: var(--text); transition: 0.3s; }
.mobile-menu { display: none; position: fixed; top: 70px; left: 0; right: 0; background: var(--blanc); padding: 24px 5%; border-bottom: 1px solid var(--gris); z-index: 999; }
.mobile-menu ul { list-style: none; display: flex; flex-direction: column; gap: 20px; }
.mobile-menu a { text-decoration: none; font-size: 15px; font-weight: 500; color: var(--text); }

/* ── HERO ── */
#hero {
  min-height: 100vh;
  display: flex; align-items: center;
  position: relative;
  overflow: hidden;
  background: var(--bleu-acier);
}
.hero-bg {
  position: absolute; inset: 0;
  background: 
    linear-gradient(135deg, var(--bleu-acier) 0%, #0d1a35 60%, #0a1020 100%);
}
.hero-pattern {
  position: absolute; inset: 0; opacity: 0.04;
  background-image: 
    repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%),
    repeating-linear-gradient(-45deg, #fff 0, #fff 1px, transparent 0, transparent 50%);
  background-size: 20px 20px;
}
.hero-accent {
  position: absolute; right: 0; top: 0; bottom: 0; width: 45%;
  background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, transparent 70%);
  border-left: 1px solid rgba(201,168,76,0.15);
}
.hero-content {
  position: relative; z-index: 2;
  padding: 120px 5% 80px;
  max-width: 700px;
}
.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.3);
  padding: 6px 16px;
  margin-bottom: 32px;
  font-size: 11px; font-weight: 600;
  color: var(--or-clair);
  text-transform: uppercase; letter-spacing: 2px;
}
.hero-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(52px, 8vw, 96px);
  line-height: 0.95;
  color: var(--blanc);
  margin-bottom: 8px;
  opacity: 0;
  animation: fadeUp 0.8s 0.2s forwards;
}
.hero-title span { color: var(--or); }
.hero-subtitle {
  font-family: 'DM Serif Display', serif;
  font-size: clamp(18px, 3vw, 28px);
  font-style: italic;
  color: rgba(255,255,255,0.6);
  margin-bottom: 28px;
  opacity: 0;
  animation: fadeUp 0.8s 0.4s forwards;
}
.hero-desc {
  font-size: 16px; color: rgba(255,255,255,0.7);
  max-width: 520px; line-height: 1.8;
  margin-bottom: 48px;
  opacity: 0;
  animation: fadeUp 0.8s 0.6s forwards;
}
.hero-buttons {
  display: flex; gap: 16px; flex-wrap: wrap;
  opacity: 0;
  animation: fadeUp 0.8s 0.8s forwards;
}
.btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 14px 32px;
  background: var(--or);
  color: var(--noir);
  text-decoration: none;
  font-weight: 700; font-size: 13px;
  text-transform: uppercase; letter-spacing: 1.5px;
  transition: all 0.25s;
  clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%);
}
.btn-primary:hover { background: var(--or-clair); transform: translateX(4px); }
.btn-secondary {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 14px 32px;
  background: transparent;
  border: 1px solid rgba(255,255,255,0.3);
  color: var(--blanc);
  text-decoration: none;
  font-size: 13px; font-weight: 500;
  letter-spacing: 0.5px;
  transition: all 0.25s;
}
.btn-secondary:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.6); }
.hero-stats {
  position: absolute; bottom: 60px; right: 5%;
  display: flex; gap: 48px; z-index: 2;
  opacity: 0; animation: fadeUp 0.8s 1s forwards;
}
.stat-item { text-align: center; }
.stat-num {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 44px; line-height: 1;
  color: var(--or);
}
.stat-label { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1.5px; margin-top: 4px; }

/* ── SECTIONS BASE ── */
section { padding: 100px 5%; }
.section-label {
  display: inline-flex; align-items: center; gap: 10px;
  font-size: 11px; font-weight: 600;
  color: var(--or);
  text-transform: uppercase; letter-spacing: 3px;
  margin-bottom: 16px;
}
.section-label::before {
  content: ''; width: 24px; height: 1px; background: var(--or);
}
.section-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(36px, 5vw, 60px);
  line-height: 1;
  color: var(--bleu-acier);
  margin-bottom: 16px;
}
.section-desc { font-size: 16px; color: var(--text-light); max-width: 560px; line-height: 1.8; }

/* ── SERVICES ── */
#services { background: var(--gris-clair); }
.services-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2px; margin-top: 64px;
}
.service-card {
  background: var(--blanc);
  padding: 48px 40px;
  position: relative; overflow: hidden;
  transition: all 0.3s;
  cursor: default;
}
.service-card::before {
  content: ''; position: absolute; bottom: 0; left: 0;
  width: 0; height: 3px; background: var(--or);
  transition: width 0.4s;
}
.service-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.service-card:hover::before { width: 100%; }
.service-icon {
  width: 56px; height: 56px;
  background: var(--bleu-acier);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 24px;
  clip-path: polygon(0 0, 80% 0, 100% 50%, 80% 100%, 0 100%);
}
.service-icon i { color: var(--or); font-size: 22px; }
.service-num {
  position: absolute; top: 24px; right: 24px;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 48px; color: var(--gris);
  line-height: 1;
}
.service-card h3 { font-family: 'Bebas Neue', sans-serif; font-size: 22px; color: var(--bleu-acier); margin-bottom: 12px; letter-spacing: 0.5px; }
.service-card p { font-size: 14px; color: var(--text-light); line-height: 1.7; }

/* ── PRODUITS ── */
.produits-tabs {
  display: flex; gap: 0;
  margin-top: 48px; margin-bottom: 48px;
  border-bottom: 2px solid var(--gris);
}
.tab-btn {
  padding: 14px 32px;
  background: none; border: none;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px; font-weight: 600;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--gris-med);
  cursor: pointer;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  transition: all 0.2s;
}
.tab-btn.active { color: var(--bleu-acier); border-bottom-color: var(--or); }
.tab-btn:hover { color: var(--bleu-acier); }
.tab-content { display: none; }
.tab-content.active { display: block; }
.produits-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 24px;
}
.produit-card {
  border: 1px solid var(--gris);
  padding: 28px 24px;
  position: relative;
  transition: all 0.25s;
  background: var(--blanc);
}
.produit-card:hover { border-color: var(--or); box-shadow: var(--shadow); }
.produit-cat {
  display: inline-block;
  padding: 3px 10px;
  background: var(--bleu-acier);
  color: var(--or);
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  margin-bottom: 16px;
}
.produit-card h3 { font-size: 16px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.produit-card p { font-size: 13px; color: var(--text-light); line-height: 1.6; margin-bottom: 16px; }
.produit-prix {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 24px; color: var(--bleu-acier);
}
.produit-prix span { font-size: 12px; font-family: 'DM Sans', sans-serif; color: var(--gris-med); font-weight: 400; }
.empty-products { text-align: center; padding: 60px; color: var(--gris-med); font-size: 15px; }

/* ── GALERIE ── */
#galerie { background: var(--noir); }
#galerie .section-title { color: var(--blanc); }
.galerie-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: auto;
  gap: 3px;
  margin-top: 64px;
}
.galerie-grid .g-item:first-child { grid-column: span 2; grid-row: span 2; }
.g-item {
  position: relative; overflow: hidden;
  aspect-ratio: 1;
  background: #1a1a1a;
  cursor: pointer;
}
.g-item:first-child { aspect-ratio: auto; }
.g-item img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.6s;
  filter: grayscale(20%);
}
.g-item:hover img { transform: scale(1.06); filter: grayscale(0%); }
.g-item-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(10,16,32,0.8) 0%, transparent 50%);
  opacity: 0; transition: opacity 0.3s;
  display: flex; align-items: flex-end; padding: 20px;
}
.g-item:hover .g-item-overlay { opacity: 1; }
.g-item-overlay p { color: var(--blanc); font-size: 13px; font-weight: 500; }
.galerie-placeholder {
  background: #1a1a1a;
  display: flex; align-items: center; justify-content: center;
  flex-direction: column; gap: 12px;
  color: #333;
  aspect-ratio: 1;
}
.galerie-placeholder i { font-size: 32px; }
.galerie-placeholder span { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }

/* ── TEMOIGNAGES ── */
#temoignages { background: var(--gris-clair); }
.temoignages-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px; margin-top: 64px;
}
.temoignage-card {
  background: var(--blanc);
  padding: 36px 32px;
  border-top: 3px solid var(--or);
  position: relative;
}
.t-quote {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 80px; line-height: 0.6;
  color: var(--or); opacity: 0.3;
  margin-bottom: 16px;
}
.t-text { font-size: 15px; line-height: 1.8; color: var(--text); margin-bottom: 24px; font-style: italic; }
.t-stars { color: var(--or); margin-bottom: 16px; font-size: 14px; }
.t-author { display: flex; align-items: center; gap: 14px; }
.t-avatar {
  width: 44px; height: 44px;
  background: var(--bleu-acier);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 18px; color: var(--or);
  border-radius: 50%;
}
.t-name { font-weight: 600; font-size: 14px; color: var(--text); }
.t-poste { font-size: 12px; color: var(--gris-med); }

/* ── FORM TEMOIGNAGE ── */
.temoignage-form-wrap {
  background: var(--blanc);
  padding: 48px; margin-top: 48px;
  border-top: 3px solid var(--bleu-acier);
}
.temoignage-form-wrap h3 {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px; color: var(--bleu-acier); margin-bottom: 32px;
}

/* ── À PROPOS ── */
#apropos {
  background: var(--bleu-acier);
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 0; padding: 0;
}
.apropos-visual {
  background: linear-gradient(135deg, #0d1a35 0%, #1a2744 100%);
  padding: 100px 5% 100px 5%;
  position: relative; overflow: hidden;
  display: flex; align-items: center;
}
.apropos-visual::before {
  content: '';
  position: absolute; inset: 0;
  background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.02) 0, rgba(255,255,255,0.02) 1px, transparent 0, transparent 50%);
  background-size: 16px 16px;
}
.apropos-big-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(80px, 12vw, 160px);
  line-height: 0.85;
  color: rgba(255,255,255,0.04);
  position: absolute; left: 5%; bottom: 0;
  pointer-events: none;
}
.apropos-features {
  display: flex; flex-direction: column; gap: 28px;
  position: relative; z-index: 1;
}
.apropos-feat {
  display: flex; gap: 16px; align-items: flex-start;
}
.apropos-feat-icon {
  width: 40px; height: 40px; flex-shrink: 0;
  background: rgba(201,168,76,0.12);
  border: 1px solid rgba(201,168,76,0.3);
  display: flex; align-items: center; justify-content: center;
}
.apropos-feat-icon i { color: var(--or); font-size: 16px; }
.apropos-feat-text h4 { font-size: 14px; font-weight: 600; color: var(--blanc); margin-bottom: 4px; }
.apropos-feat-text p { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.6; }
.apropos-content {
  padding: 100px 8%;
  background: var(--blanc);
  display: flex; flex-direction: column; justify-content: center;
}
.apropos-content .section-title { color: var(--bleu-acier); }
.apropos-content p { font-size: 16px; color: var(--text-light); line-height: 1.9; margin-bottom: 20px; }
.apropos-content a { margin-top: 16px; }

/* ── CONTACT / DEVIS ── */
#contact { background: var(--blanc); }
.contact-grid {
  display: grid; grid-template-columns: 1fr 1.4fr;
  gap: 80px; margin-top: 64px;
}
.contact-info { }
.contact-item {
  display: flex; gap: 16px; align-items: flex-start;
  margin-bottom: 32px;
}
.contact-item-icon {
  width: 48px; height: 48px; flex-shrink: 0;
  background: var(--bleu-acier);
  display: flex; align-items: center; justify-content: center;
  clip-path: polygon(0 0, 80% 0, 100% 50%, 80% 100%, 0 100%);
}
.contact-item-icon i { color: var(--or); font-size: 18px; }
.contact-item h4 { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
.contact-item p { font-size: 14px; color: var(--text-light); }

/* ── FORMS ── */
.form-group { margin-bottom: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
label { display: block; font-size: 12px; font-weight: 600; color: var(--text); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
input, textarea, select {
  width: 100%; padding: 13px 16px;
  border: 1px solid var(--gris);
  background: var(--gris-clair);
  font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text);
  outline: none; transition: border 0.2s;
  border-radius: var(--radius);
  appearance: none;
}
input:focus, textarea:focus, select:focus {
  border-color: var(--or); background: var(--blanc);
}
textarea { min-height: 130px; resize: vertical; }
.btn-form {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 14px 36px;
  background: var(--bleu-acier); color: var(--blanc);
  border: none; cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.5px;
  transition: all 0.25s;
  clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%);
}
.btn-form:hover { background: var(--or); color: var(--noir); }
.alert-success {
  padding: 14px 20px;
  background: #e8f5e9; border-left: 3px solid #4caf50;
  color: #2e7d32; font-size: 14px; margin-bottom: 24px;
}
.alert-error {
  padding: 14px 20px;
  background: #fce4e4; border-left: 3px solid var(--rouge);
  color: var(--rouge); font-size: 14px; margin-bottom: 24px;
}

/* ── FOOTER ── */
footer {
  background: var(--noir); color: rgba(255,255,255,0.6);
  padding: 80px 5% 32px;
}
.footer-grid {
  display: grid; grid-template-columns: 2fr 1fr 1fr;
  gap: 60px; margin-bottom: 60px;
}
.footer-brand .brand-name {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 28px; color: var(--blanc); letter-spacing: 1px;
  margin-bottom: 16px;
}
.footer-brand p { font-size: 14px; line-height: 1.8; max-width: 280px; }
.footer-col h4 {
  font-size: 12px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 2px;
  color: var(--or); margin-bottom: 20px;
}
.footer-col ul { list-style: none; }
.footer-col ul li { margin-bottom: 10px; }
.footer-col ul a { text-decoration: none; color: rgba(255,255,255,0.5); font-size: 14px; transition: color 0.2s; }
.footer-col ul a:hover { color: var(--or); }
.footer-bottom {
  border-top: 1px solid #1a1a1a;
  padding-top: 28px;
  display: flex; justify-content: space-between; align-items: center;
}
.footer-bottom p { font-size: 13px; }
.footer-socials { display: flex; gap: 16px; }
.footer-socials a {
  width: 36px; height: 36px;
  border: 1px solid #2a2a2a;
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,0.4); text-decoration: none;
  font-size: 14px; transition: all 0.2s;
}
.footer-socials a:hover { border-color: var(--or); color: var(--or); }

/* ── ANIMATIONS ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
.reveal {
  opacity: 0; transform: translateY(24px);
  transition: opacity 0.6s, transform 0.6s;
}
.reveal.visible { opacity: 1; transform: translateY(0); }

/* ── ADMIN LINK ── */
.admin-bar {
  position: fixed; bottom: 24px; right: 24px; z-index: 999;
}
.admin-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px;
  background: var(--bleu-acier);
  color: var(--blanc);
  text-decoration: none;
  font-size: 12px; font-weight: 600;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  transition: all 0.2s;
  border: 1px solid rgba(201,168,76,0.3);
}
.admin-btn:hover { background: var(--or); color: var(--noir); }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  #apropos { grid-template-columns: 1fr; }
  .apropos-visual { min-height: 400px; }
  .contact-grid { grid-template-columns: 1fr; gap: 48px; }
}
@media (max-width: 768px) {
  .nav-links { display: none; }
  .hamburger { display: flex; }
  .hero-stats { display: none; }
  .galerie-grid { grid-template-columns: repeat(2, 1fr); }
  .galerie-grid .g-item:first-child { grid-column: 1; grid-row: 1; }
  .footer-grid { grid-template-columns: 1fr; gap: 36px; }
  .temoignage-form-wrap { padding: 28px 20px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
  <a href="#hero" class="nav-logo">
    <div class="nav-logo-icon"><i class="fas fa-truck"></i></div>
    <div class="nav-logo-text">
      <div class="brand">AL AMIINE</div>
      <div class="tagline">Logistique</div>
    </div>
  </a>
  <ul class="nav-links">
    <li><a href="#services">Services</a></li>
    <li><a href="#produits">Produits</a></li>
    <li><a href="#galerie">Galerie</a></li>
    <li><a href="#temoignages">Témoignages</a></li>
    <li><a href="#apropos">À propos</a></li>
    <li><a href="#contact" class="nav-cta">Devis gratuit</a></li>
  </ul>
  <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
<div class="mobile-menu" id="mobileMenu">
  <ul>
    <li><a href="#services" onclick="toggleMenu()">Services</a></li>
    <li><a href="#produits" onclick="toggleMenu()">Produits</a></li>
    <li><a href="#galerie" onclick="toggleMenu()">Galerie</a></li>
    <li><a href="#temoignages" onclick="toggleMenu()">Témoignages</a></li>
    <li><a href="#apropos" onclick="toggleMenu()">À propos</a></li>
    <li><a href="#contact" onclick="toggleMenu()">Devis gratuit</a></li>
  </ul>
</div>

<!-- HERO -->
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-accent"></div>
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-shield-alt"></i> Fiabilité · Qualité · Ponctualité</div>
    <h1 class="hero-title">AL AMIINE<br><span>LOGISTIQUE</span></h1>
    <p class="hero-subtitle">Transport & Matériaux de Construction</p>
    <p class="hero-desc">Votre partenaire de confiance pour le transport de matériel de construction et la vente de fer et bois de qualité supérieure. Livraison rapide, prix compétitifs.</p>
    <div class="hero-buttons">
      <a href="#contact" class="btn-primary"><i class="fas fa-file-invoice"></i> Demander un devis</a>
      <a href="#services" class="btn-secondary"><i class="fas fa-arrow-down"></i> Nos services</a>
    </div>
  </div>
  <div class="hero-stats">
    <div class="stat-item"><div class="stat-num">500+</div><div class="stat-label">Clients satisfaits</div></div>
    <div class="stat-item"><div class="stat-num">10+</div><div class="stat-label">Ans d'expérience</div></div>
    <div class="stat-item"><div class="stat-num">24h</div><div class="stat-label">Délai livraison</div></div>
  </div>
</section>

<!-- SERVICES -->
<section id="services">
  <div class="reveal">
    <span class="section-label">Ce que nous faisons</span>
    <h2 class="section-title">NOS SERVICES</h2>
    <p class="section-desc">Des solutions complètes pour tous vos besoins en matériaux et transport de construction.</p>
  </div>
  <div class="services-grid reveal">
    <div class="service-card">
      <span class="service-num">01</span>
      <div class="service-icon"><i class="fas fa-truck"></i></div>
      <h3>Transport de Matériaux</h3>
      <p>Livraison rapide et sécurisée de tous types de matériaux de construction sur vos chantiers, partout dans la région.</p>
    </div>
    <div class="service-card">
      <span class="service-num">02</span>
      <div class="service-icon"><i class="fas fa-industry"></i></div>
      <h3>Vente de Fer</h3>
      <p>Large gamme de produits sidérurgiques : fer à béton, profilés, tôles, treillis soudés. Qualité certifiée aux meilleures normes.</p>
    </div>
    <div class="service-card">
      <span class="service-num">03</span>
      <div class="service-icon"><i class="fas fa-tree"></i></div>
      <h3>Vente de Bois</h3>
      <p>Bois de charpente, planches de coffrage, contreplaqués et madriers. Sélection rigoureuse pour vos projets de construction.</p>
    </div>
    <div class="service-card">
      <span class="service-num">04</span>
      <div class="service-icon"><i class="fas fa-warehouse"></i></div>
      <h3>Stockage & Logistique</h3>
      <p>Solutions de stockage temporaire et gestion logistique de vos matériaux pour une organisation optimale de vos chantiers.</p>
    </div>
    <div class="service-card">
      <span class="service-num">05</span>
      <div class="service-icon"><i class="fas fa-calculator"></i></div>
      <h3>Devis & Conseil</h3>
      <p>Nos experts vous accompagnent dans l'estimation de vos besoins et vous proposent des solutions adaptées à votre budget.</p>
    </div>
    <div class="service-card">
      <span class="service-num">06</span>
      <div class="service-icon"><i class="fas fa-handshake"></i></div>
      <h3>Partenariats BTP</h3>
      <p>Contrats cadres pour entreprises de construction et promoteurs immobiliers. Tarifs préférentiels et service dédié.</p>
    </div>
  </div>
</section>

<!-- PRODUITS -->
<section id="produits">
  <div class="reveal">
    <span class="section-label">Notre catalogue</span>
    <h2 class="section-title">NOS PRODUITS</h2>
    <p class="section-desc">Une gamme complète de fer et bois sélectionnés pour la qualité et la durabilité.</p>
  </div>
  <div class="produits-tabs reveal">
    <button class="tab-btn active" onclick="switchTab('fer', this)">
      <i class="fas fa-industry"></i> Fer & Acier
    </button>
    <button class="tab-btn" onclick="switchTab('bois', this)">
      <i class="fas fa-tree"></i> Bois & Boiseries
    </button>
  </div>

  <!-- FER -->
  <div class="tab-content active" id="tab-fer">
    <?php if (!empty($produits_fer)): ?>
    <div class="produits-grid reveal">
      <?php foreach ($produits_fer as $p): ?>
      <div class="produit-card">
        <span class="produit-cat">Fer & Acier</span>
        <h3><?= sanitize($p['nom']) ?></h3>
        <p><?= sanitize($p['description'] ?? 'Produit de qualité supérieure') ?></p>
        <?php if ($p['prix']): ?>
        <div class="produit-prix"><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA <span>/ <?= sanitize($p['unite'] ?? 'unité') ?></span></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-products"><i class="fas fa-box-open" style="font-size:40px;margin-bottom:16px;display:block"></i>Aucun produit disponible pour le moment. Contactez-nous pour un devis personnalisé.</div>
    <?php endif; ?>
  </div>

  <!-- BOIS -->
  <div class="tab-content" id="tab-bois">
    <?php if (!empty($produits_bois)): ?>
    <div class="produits-grid reveal">
      <?php foreach ($produits_bois as $p): ?>
      <div class="produit-card">
        <span class="produit-cat" style="background:var(--or);color:var(--noir)">Bois</span>
        <h3><?= sanitize($p['nom']) ?></h3>
        <p><?= sanitize($p['description'] ?? 'Bois de qualité sélectionné') ?></p>
        <?php if ($p['prix']): ?>
        <div class="produit-prix"><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA <span>/ <?= sanitize($p['unite'] ?? 'unité') ?></span></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-products"><i class="fas fa-tree" style="font-size:40px;margin-bottom:16px;display:block"></i>Aucun produit disponible. Contactez-nous pour nos tarifs bois.</div>
    <?php endif; ?>
  </div>
</section>

<!-- GALERIE -->
<section id="galerie">
  <div class="reveal">
    <span class="section-label" style="color:var(--or-clair)">Nos réalisations</span>
    <h2 class="section-title">GALERIE</h2>
  </div>
  <div class="galerie-grid reveal">
    <?php
    $placeholders = [
      ['icon'=>'fa-truck','label'=>'Livraison chantier'],
      ['icon'=>'fa-industry','label'=>'Stock de fer'],
      ['icon'=>'fa-tree','label'=>'Bois de charpente'],
      ['icon'=>'fa-hard-hat','label'=>'Chantier en cours'],
      ['icon'=>'fa-warehouse','label'=>'Notre entrepôt'],
    ];
    if (!empty($galerie)) {
      foreach ($galerie as $g) {
        echo '<div class="g-item"><img src="'.sanitize($g['image']).'" alt="'.sanitize($g['titre'] ?? '').'" loading="lazy">
        <div class="g-item-overlay"><p>'.sanitize($g['titre'] ?? '').'</p></div></div>';
      }
    } else {
      foreach ($placeholders as $i => $ph) {
        echo '<div class="g-item galerie-placeholder"><i class="fas '.$ph['icon'].'"></i><span>'.$ph['label'].'</span></div>';
      }
    }
    ?>
  </div>
</section>

<!-- TEMOIGNAGES -->
<section id="temoignages">
  <div class="reveal">
    <span class="section-label">Ce qu'ils disent</span>
    <h2 class="section-title">TÉMOIGNAGES</h2>
    <p class="section-desc">La confiance de nos clients est notre plus grande fierté.</p>
  </div>
  <div class="temoignages-grid reveal">
    <?php foreach ($temoignages as $t): ?>
    <div class="temoignage-card">
      <div class="t-quote">"</div>
      <p class="t-text"><?= sanitize($t['message']) ?></p>
      <div class="t-stars"><?= str_repeat('★', intval($t['note'])) ?><?= str_repeat('☆', 5 - intval($t['note'])) ?></div>
      <div class="t-author">
        <div class="t-avatar"><?= strtoupper(substr($t['nom'], 0, 1)) ?></div>
        <div>
          <div class="t-name"><?= sanitize($t['nom']) ?></div>
          <?php if ($t['poste']): ?><div class="t-poste"><?= sanitize($t['poste']) ?></div><?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Formulaire témoignage -->
  <div class="temoignage-form-wrap reveal">
    <h3>Partagez votre expérience</h3>
    <?php if (!empty($msg_success_t)): ?>
    <div class="alert-success"><i class="fas fa-check-circle"></i> <?= sanitize($msg_success_t) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="form_temoignage" value="1">
      <div class="form-row">
        <div class="form-group"><label>Votre nom *</label><input type="text" name="t_nom" required></div>
        <div class="form-group"><label>Votre poste / entreprise</label><input type="text" name="t_poste"></div>
      </div>
      <div class="form-group"><label>Votre message *</label><textarea name="t_message" required placeholder="Partagez votre expérience avec AL AMIINE LOGISTIQUE..."></textarea></div>
      <div class="form-group">
        <label>Note</label>
        <select name="t_note">
          <option value="5">★★★★★ Excellent</option>
          <option value="4">★★★★☆ Très bien</option>
          <option value="3">★★★☆☆ Bien</option>
        </select>
      </div>
      <button type="submit" class="btn-form"><i class="fas fa-paper-plane"></i> Envoyer mon témoignage</button>
    </form>
  </div>
</section>

<!-- À PROPOS -->
<section id="apropos" style="padding:0">
  <div class="apropos-visual">
    <div class="apropos-big-text">TRUST</div>
    <div class="apropos-features">
      <div class="apropos-feat">
        <div class="apropos-feat-icon"><i class="fas fa-award"></i></div>
        <div class="apropos-feat-text">
          <h4>Qualité certifiée</h4>
          <p>Nos matériaux répondent aux normes en vigueur pour garantir la sécurité de vos ouvrages.</p>
        </div>
      </div>
      <div class="apropos-feat">
        <div class="apropos-feat-icon"><i class="fas fa-clock"></i></div>
        <div class="apropos-feat-text">
          <h4>Livraison en 24h</h4>
          <p>Notre flotte de camions assure une livraison rapide sur toute la zone de couverture.</p>
        </div>
      </div>
      <div class="apropos-feat">
        <div class="apropos-feat-icon"><i class="fas fa-tags"></i></div>
        <div class="apropos-feat-text">
          <h4>Prix compétitifs</h4>
          <p>Des tarifs justes et transparents, sans frais cachés. Devis gratuit sous 24h.</p>
        </div>
      </div>
      <div class="apropos-feat">
        <div class="apropos-feat-icon"><i class="fas fa-headset"></i></div>
        <div class="apropos-feat-text">
          <h4>Support dédié</h4>
          <p>Une équipe à votre écoute pour vous conseiller et répondre à toutes vos questions.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="apropos-content">
    <span class="section-label">Notre histoire</span>
    <h2 class="section-title">À PROPOS<br>DE NOUS</h2>
    <p>AL AMIINE LOGISTIQUE est une entreprise spécialisée dans le transport de matériaux de construction et la vente de fer et bois. Depuis plus de 10 ans, nous accompagnons les professionnels du BTP et les particuliers dans la réalisation de leurs projets.</p>
    <p>Notre engagement : vous fournir des matériaux de qualité, livrés à temps et au meilleur prix. Notre équipe de professionnels expérimentés est à votre disposition pour vous conseiller et vous offrir un service sur mesure.</p>
    <a href="#contact" class="btn-primary" style="align-self:flex-start;margin-top:16px"><i class="fas fa-envelope"></i> Nous contacter</a>
  </div>
</section>

<!-- CONTACT / DEVIS -->
<section id="contact">
  <div class="reveal">
    <span class="section-label">Travaillons ensemble</span>
    <h2 class="section-title">CONTACT & DEVIS</h2>
    <p class="section-desc">Remplissez le formulaire ci-dessous pour obtenir votre devis gratuit sous 24h.</p>
  </div>
  <div class="contact-grid">
    <div class="contact-info reveal">
      <div class="contact-item">
        <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div><h4>Adresse</h4><p>Dakar, Sénégal</p></div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
        <div><h4>Téléphone</h4><p>+221 XX XXX XX XX</p></div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon"><i class="fab fa-whatsapp"></i></div>
        <div><h4>WhatsApp</h4><p>+221 XX XXX XX XX</p></div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
        <div><h4>Email</h4><p>contact@alaamine-logistique.com</p></div>
      </div>
      <div class="contact-item">
        <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
        <div><h4>Horaires</h4><p>Lun – Sam : 7h00 – 19h00</p></div>
      </div>
    </div>
    <div class="reveal">
      <?php if ($msg_success): ?>
      <div class="alert-success"><i class="fas fa-check-circle"></i> <?= sanitize($msg_success) ?></div>
      <?php endif; ?>
      <?php if ($msg_error): ?>
      <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?= sanitize($msg_error) ?></div>
      <?php endif; ?>
      <form method="POST">
        <input type="hidden" name="form_devis" value="1">
        <div class="form-row">
          <div class="form-group"><label>Nom complet *</label><input type="text" name="nom" required placeholder="Votre nom"></div>
          <div class="form-group"><label>Email *</label><input type="email" name="email" required placeholder="email@exemple.com"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Téléphone *</label><input type="tel" name="telephone" required placeholder="+221 XX XXX XX XX"></div>
          <div class="form-group">
            <label>Service souhaité</label>
            <select name="service">
              <option value="">-- Sélectionner --</option>
              <option>Transport de matériaux</option>
              <option>Achat de fer</option>
              <option>Achat de bois</option>
              <option>Stockage & Logistique</option>
              <option>Autre</option>
            </select>
          </div>
        </div>
        <div class="form-group"><label>Message *</label><textarea name="message" required placeholder="Décrivez votre projet, les quantités estimées, la localisation de livraison..."></textarea></div>
        <button type="submit" class="btn-form"><i class="fas fa-paper-plane"></i> Envoyer ma demande</button>
      </form>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="brand-name">AL AMIINE LOGISTIQUE</div>
      <p>Votre partenaire de confiance pour le transport de matériaux de construction et la vente de fer et bois de qualité à Dakar et environs.</p>
    </div>
    <div class="footer-col">
      <h4>Navigation</h4>
      <ul>
        <li><a href="#services">Nos services</a></li>
        <li><a href="#produits">Nos produits</a></li>
        <li><a href="#galerie">Galerie</a></li>
        <li><a href="#temoignages">Témoignages</a></li>
        <li><a href="#apropos">À propos</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Produits</h4>
      <ul>
        <li><a href="#produits">Fer à béton</a></li>
        <li><a href="#produits">Profilés acier</a></li>
        <li><a href="#produits">Tôles</a></li>
        <li><a href="#produits">Bois de charpente</a></li>
        <li><a href="#produits">Contreplaqués</a></li>
        <li><a href="#produits">Madriers</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© <?= date('Y') ?> AL AMIINE LOGISTIQUE. Tous droits réservés.</p>
    <div class="footer-socials">
      <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

<!-- Admin link -->
<div class="admin-bar">
  <a href="admin/login.php" class="admin-btn"><i class="fas fa-lock"></i> Admin</a>
</div>

<script>
// Navbar scroll
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Mobile menu
function toggleMenu() {
  const m = document.getElementById('mobileMenu');
  m.style.display = m.style.display === 'block' ? 'none' : 'block';
}

// Product tabs
function switchTab(type, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('tab-' + type).classList.add('active');
}

// Scroll reveal
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>
