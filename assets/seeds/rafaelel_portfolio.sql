-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 09, 2026 at 01:56 AM
-- Server version: 8.0.46-cll-lve
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rafaelel_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `issuing_organization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `credential_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `credential_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `name`, `issuing_organization`, `issue_date`, `expiration_date`, `credential_id`, `credential_url`, `display_order`) VALUES
(8, 'Cybersecurity and Ethical Hacking', 'Big School', '2026-04-01', NULL, NULL, NULL, 1),
(9, 'AI Development: From Design to Production', 'Big School', '2026-03-01', NULL, NULL, NULL, 2),
(10, 'English for Computer Science 1', 'Cisco Networking Academy', '2026-07-01', NULL, NULL, NULL, 3),
(11, 'Introduction to Data Science', 'Cisco Networking Academy', '2026-04-01', NULL, NULL, NULL, 4),
(12, 'Professor Scrum Foundation (SFPC)', 'Certiprof', '2026-01-01', NULL, NULL, NULL, 5),
(13, 'Operating Systems Fundamentals', 'Cisco Networking Academy', '2025-11-01', NULL, NULL, NULL, 6),
(14, 'Azure Fundamentals AZ-900 Training', 'FS Tetuán & IT Global Institute', '2025-06-01', NULL, NULL, NULL, 7);

-- --------------------------------------------------------

--
-- Table structure for table `code_samples`
--

CREATE TABLE `code_samples` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `language` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `code` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `code_samples`
--

INSERT INTO `code_samples` (`id`, `project_id`, `language`, `code`, `description`, `display_order`) VALUES
(1, 5, 'React JS', 'import React, { useState, useEffect } from \'react\';\r\nimport Header from \'./components/Header\';\r\nimport CurrentWeather from \'./components/CurrentWeather\';\r\nimport HourlyForecast from \'./components/HourlyForecast\';\r\nimport DailyForecast from \'./components/DailyForecast\';\r\nimport AirQuality from \'./components/AirQuality\';\r\nimport WeatherDetails from \'./components/WeatherDetails\';\r\nimport \'./styles/App.css\';\r\nimport \'./styles/components.css\';\r\nfunction App() {\r\n  const [weatherData, setWeatherData] = useState({\r\n    location: \'Ciudad de México\',\r\n    temperature: 22,\r\n    condition: \'Soleado\',\r\n    highLow: { high: 25, low: 18 }\r\n  });\r\n\r\n  // Simular cambio de condiciones (para demostración del diseño)\r\n  useEffect(() => {\r\n    const conditions = [\'Soleado\', \'Parcialmente nublado\', \'Nublado\', \'Lluvioso\'];\r\n    const interval = setInterval(() => {\r\n      const randomCondition = conditions[Math.floor(Math.random() * conditions.length)];\r\n      setWeatherData(prev => ({\r\n        ...prev,\r\n        condition: randomCondition,\r\n        temperature: Math.floor(Math.random() * 15) + 15\r\n      }));\r\n    }, 10000);\r\n\r\n    return () => clearInterval(interval);\r\n  }, []);\r\n\r\n  const getBackgroundGradient = (condition) => {\r\n    switch(condition) {\r\n      case \'Soleado\': return \'gradient-sunny\';\r\n      case \'Parcialmente nublado\': return \'gradient-cloudy\';\r\n      case \'Nublado\': return \'gradient-cloudy\';\r\n      case \'Lluvioso\': return \'gradient-rainy\';\r\n      default: return \'gradient-sunny\';\r\n    }\r\n  };\r\n\r\n  return (\r\n    <div className={`app-container ${getBackgroundGradient(weatherData.condition)}`}>\r\n      <div className=\"container-fluid px-4 py-3\">\r\n        <Header location={weatherData.location} />\r\n        <CurrentWeather \r\n          temperature={weatherData.temperature}\r\n          condition={weatherData.condition}\r\n          highLow={weatherData.highLow}\r\n        />\r\n        <HourlyForecast />\r\n        <DailyForecast />\r\n        <div className=\"row\">\r\n          <div className=\"col-md-6\">\r\n            <AirQuality />\r\n          </div>\r\n          <div className=\"col-md-6\">\r\n            <WeatherDetails />\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  );\r\n}\r\n\r\nexport default App;', NULL, 0),
(2, 6, 'JAVA', 'package pfm.backend.model;\r\n\r\nimport jakarta.persistence.*;\r\nimport lombok.Data;\r\nimport lombok.NoArgsConstructor;\r\nimport lombok.AllArgsConstructor;\r\nimport java.time.LocalDate;\r\nimport java.util.List;\r\n\r\n@Entity\r\n@Table(name = \"exam\")\r\n@Data\r\n@NoArgsConstructor\r\n@AllArgsConstructor\r\npublic class Exam {\r\n    @Id\r\n    @GeneratedValue(strategy = GenerationType.IDENTITY)\r\n    private Long id;\r\n\r\n    @Column(nullable = false)\r\n    private String nom;\r\n\r\n    @Column\r\n    private String description;\r\n    \r\n    @Column\r\n    private int nombreQuestions = 1;\r\n    \r\n    @Column\r\n    private LocalDate date;\r\n    \r\n    @Enumerated(EnumType.STRING)\r\n    @Column(nullable = false)\r\n    private Semestre semestre;\r\n\r\n    @Column(name = \"lien_examen\")\r\n    private String lienExamen = \"http://localhost:4200/exam/\";\r\n\r\n    @ManyToOne(fetch = FetchType.EAGER)\r\n    @JoinColumn(name = \"professor_id\", nullable = false)\r\n    private Professor professor;\r\n    \r\n    @OneToMany(mappedBy = \"exam\", fetch = FetchType.LAZY, cascade = CascadeType.ALL)\r\n    private List<Question> questions;\r\n}', NULL, 0),
(3, 1, 'PHP', '<?php\r\n\r\nnamespace App\\Entity;\r\n\r\nuse App\\Repository\\RendezvousRepository;\r\nuse Doctrine\\DBAL\\Types\\Types;\r\nuse Doctrine\\ORM\\Mapping as ORM;\r\n\r\n#[ORM\\Entity(repositoryClass: RendezvousRepository::class)]\r\n#[ORM\\Index(columns: [\'actif\'], name: \'idx_rendezvous_actif\')]\r\n#[ORM\\Index(columns: [\'date\'], name: \'idx_rendezvous_date\')]\r\n#[ORM\\UniqueConstraint(columns: [\"dentiste_id\", \"date\"])]\r\n\r\nclass Rendezvous\r\n{\r\n    #[ORM\\Id]\r\n    #[ORM\\GeneratedValue]\r\n    #[ORM\\Column]\r\n    private ?int $id = null;\r\n\r\n    #[ORM\\Column(type: Types::DATETIME_MUTABLE)]\r\n    private ?\\DateTimeInterface $date = null;\r\n\r\n    #[ORM\\ManyToOne(inversedBy: \'rendezvouses_dentiste\')]\r\n    #[ORM\\JoinColumn(nullable: false)]\r\n    private ?Utilisateur $dentiste = null;\r\n\r\n    #[ORM\\ManyToOne (inversedBy: \'rendezvouses_patient\')]\r\n    #[ORM\\JoinColumn(nullable: false)]\r\n    private ?Utilisateur $patient = null;\r\n\r\n    #[ORM\\Column(length: 255)]\r\n    private ?string $service = null;\r\n\r\n    #[ORM\\Column(length: 255)]\r\n    private ?string $observation = null;\r\n\r\n    #[ORM\\Column]\r\n    private ?bool $actif = null;\r\n\r\n    // Getters and setters...\r\n\r\n    public function getId(): ?int\r\n    {\r\n        return $this->id;\r\n    }\r\n\r\n    public function getDate(): ?\\DateTimeInterface\r\n    {\r\n        return $this->date;\r\n    }\r\n\r\n    public function setDate(\\DateTimeInterface $date): static\r\n    {\r\n        $this->date = $date;\r\n\r\n        return $this;\r\n    }\r\n\r\n    public function getDentiste(): ?Utilisateur\r\n    {\r\n        return $this->dentiste;\r\n    }\r\n\r\n    public function setDentiste(?Utilisateur $dentiste): static\r\n    {\r\n        $this->dentiste = $dentiste;\r\n\r\n        return $this;\r\n    }\r\n\r\n    public function getPatient(): ?Utilisateur\r\n    {\r\n        return $this->patient;\r\n    }\r\n\r\n    public function setPatient(?Utilisateur $patient): static\r\n    {\r\n        $this->patient = $patient;\r\n\r\n        return $this;\r\n    }\r\n\r\n    public function getService(): ?string\r\n    {\r\n        return $this->service;\r\n    }\r\n\r\n    public function setService(string $service): static\r\n    {\r\n        $this->service = $service;\r\n\r\n        return $this;\r\n    }\r\n\r\n    public function getObservation(): ?string\r\n    {\r\n        return $this->observation;\r\n    }\r\n\r\n    public function setObservation(string $observation): static\r\n    {\r\n        $this->observation = $observation;\r\n\r\n        return $this;\r\n    }\r\n\r\n    public function isActif(): ?bool\r\n    {\r\n        return $this->actif;\r\n    }\r\n\r\n    public function setActif(bool $actif): static\r\n    {\r\n        $this->actif = $actif;\r\n\r\n        return $this;\r\n    }\r\n}\r\n', NULL, 0),
(4, 8, 'HTML & PHP', '<?php\r\nrequire_once \'controller.php\';\r\n\r\n$controller = new Controller();\r\n$matieres = $controller->getMatieres();\r\n$evenements = $controller->getEvenementsFuturs();\r\n$evenements_comp = $controller->getEvenements();\r\n?>\r\n<!DOCTYPE html>\r\n<html lang=\"fr\">\r\n\r\n<head>\r\n  <meta charset=\"UTF-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title>Accueil - IWA</title>\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css\">\r\n  <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"./img/logo_3.png\">\r\n  <link href=\"https://fonts.googleapis.com/css?family=Muli:300,400,700,900\" rel=\"stylesheet\">\r\n  <link rel=\"stylesheet\" href=\"./fonts/icomoon/style.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/bootstrap.min.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/jquery-ui.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/owl.carousel.min.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/owl.theme.default.min.css\">\r\n  <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/jquery.fancybox.min.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/bootstrap-datepicker.css\">\r\n  <link rel=\"stylesheet\" href=\"./fonts/flaticon/font/flaticon.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/aos.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/jquery.mb.YTPlayer.min.css\">\r\n  <link rel=\"stylesheet\" href=\"./css/style.css\">\r\n</head>\r\n\r\n<body data-spy=\"scroll\" data-target=\".site-navbar-target\" data-offset=\"300\" style=\"text-align: justify;\">\r\n\r\n  <div class=\"site-wrap\">\r\n\r\n    <div class=\"site-mobile-menu site-navbar-target\">\r\n      <div class=\"site-mobile-menu-header\">\r\n        <div class=\"site-mobile-menu-close mt-3\">\r\n          <span class=\"icon-close2 js-menu-toggle\"></span>\r\n        </div>\r\n      </div>\r\n      <div class=\"site-mobile-menu-body\"></div>\r\n    </div>\r\n\r\n\r\n    <div class=\"py-2 bg-light\">\r\n      <div class=\"container\">\r\n        <div class=\"row align-items-center\">\r\n          <div class=\"col-lg-8 d-none d-lg-block\">\r\n            <a href=\"contact.php\" class=\"small mr-3\"><span class=\"icon-question-circle-o mr-2\"></span> Vous avez des\r\n              questions\r\n              ?</a>\r\n            <a href=\"tel: +212662102167\" class=\"small mr-3\"><span class=\"icon-phone2 mr-2\"></span> 0662102167</a>\r\n            <a href=\"mailto:iwa@uae.ac.ma \" class=\"small mr-3\"><span class=\"icon-envelope-o mr-2\"></span>\r\n              iwa@uae.ac.ma</a>\r\n          </div>\r\n          <div class=\"col-lg-4 text-right\">\r\n            <a href=\"authentification.php\" class=\"small mr-3\"><span class=\"icon-unlock-alt\"></span>\r\n              Se connecter</a>\r\n            <a href=\"preinscription.php\" class=\"small btn btn-primary px-4 py-2 rounded-0\"><span\r\n                class=\"icon-users\"></span>\r\n              Pré-inscription</a>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n    <header class=\"site-navbar py-4 js-sticky-header site-navbar-target\" role=\"banner\">\r\n\r\n      <div class=\"container\">\r\n        <div class=\"d-flex align-items-center\">\r\n          <div class=\"site-logo\">\r\n            <a href=\"index.php\" class=\"d-block\">\r\n              <img src=\"img/logo.png\" alt=\"Image\" class=\"img-fluid\">\r\n            </a>\r\n          </div>\r\n          <div class=\"mr-auto\">\r\n            <nav class=\"site-navigation position-relative text-right\" role=\"navigation\">\r\n              <ul class=\"site-menu main-menu js-clone-nav mr-auto d-none d-lg-block\">\r\n                <li class=\"active\">\r\n                  <a href=\"index.php\" class=\"nav-link text-left\">Accueil</a>\r\n                </li>\r\n                <li class=\"has-children\">\r\n                  <a href=\"#\" class=\"nav-link text-left\">Formation IWA</a>\r\n                  <ul class=\"dropdown\">\r\n                    <li><a href=\"apropos.php\" class=\"nav-link text-left\">À propos d\' IWA</a></li>\r\n                    <li><a href=\"programme.php\">Programme de la formation</a></li>\r\n                  </ul>\r\n                </li>\r\n                <li class=\"has-children\">\r\n                  <a href=\"\" class=\"nav-link text-left\">Autres</a>\r\n                  <ul class=\"dropdown\">\r\n                    <li><a href=\"evenements.php\" class=\"nav-link text-left\">Evénements</a></li>\r\n                    <li><a href=\"nouvelles.php\">Nouvelles</a></li>\r\n                    <li><a href=\"galerie_de_photos.php\" class=\"nav-link text-left\">Galerie</a></li>\r\n                  </ul>\r\n                </li>\r\n                <li><a href=\"authentification.php\" class=\"nav-link text-left\">Espace utilisateurs</a></li>\r\n                <li>\r\n                  <a href=\"contact.php\" class=\"nav-link text-left\">Contact</a>\r\n                </li>\r\n            </nav>\r\n\r\n          </div>\r\n          <div class=\"ml-auto\">\r\n            <div class=\"social-wrap\">\r\n              <a href=\"#\"><span class=\"icon-facebook\"></span></a>\r\n              <a href=\"#\"><span class=\"icon-twitter\"></span></a>\r\n              <a href=\"#\"><span class=\"icon-linkedin\"></span></a>\r\n\r\n              <a href=\"#\" class=\"d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black\"><span\r\n                  class=\"icon-menu h3\"></span></a>\r\n            </div>\r\n          </div>\r\n\r\n        </div>\r\n      </div>\r\n\r\n    </header>\r\n\r\n    <div class=\"hero-slide owl-carousel\"\r\n      style=\"width: 75%; height: 90vh; margin: 0 auto; display: flex; flex-direction: column; justify-content: center;\">\r\n      <?php foreach ($evenements as $evenement) { ?>\r\n        <a href=\"nouvelles.php#<?= htmlspecialchars($evenement[\'evenement\']) ?>\">\r\n          <div class=\"intro-section\" style=\"background-image: url(\'./img/photo_evenements/backups/<?php echo htmlspecialchars($evenement[\'photo\']); ?>.png\'); \r\n           background-position: center 30%; \r\n        background-repeat: no-repeat; \r\n        background-size: contain; \r\n        height: 95%;\">\r\n            <div class=\"container\" style=\"display: flex; align-items: center; justify-content: center; height: 100%;\">\r\n              <div class=\"row align-items-center\">\r\n                <div class=\"col-lg-12 mx-auto text-center\" data-aos=\"fade-up\">\r\n                  <h2 style=\"color:#51be78;\"><?= htmlspecialchars($evenement[\'evenement\']) ?></h2>\r\n                </div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n        </a>\r\n      <?php } ?>\r\n    </div>\r\n\r\n    <div class=\"site-section\">\r\n      <div class=\"container\">\r\n        <div class=\"row mb-5 justify-content-center text-center\">\r\n          <div class=\"col-lg-4 mb-5\">\r\n            <h2 class=\"section-title-underline mb-5\">\r\n              <span>Offres académiques</span>\r\n            </h2>\r\n          </div>\r\n        </div>\r\n        <div class=\"row\">\r\n          <div class=\"col-lg-4 col-md-6 mb-4 mb-lg-0\">\r\n\r\n            <div class=\"feature-1 border\">\r\n              <div class=\"icon-wrapper bg-primary\">\r\n                <span class=\"flaticon-mortarboard text-white\"></span>\r\n              </div>\r\n              <div class=\"feature-1-content\">\r\n                <h2>Débouchés de la formation</h2>\r\n                <p style=\"text-align: justify;\">L\'un des avantages des lauréats du DCESS IWA sera qu\'ils auront un\r\n                  profil très recherché sur le marché du\r\n                  travail.</p>\r\n                <p><a href=\"apropos.php#debouches\" class=\"btn btn-primary px-4 rounded-0\">En savoir plus</a></p>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class=\"col-lg-4 col-md-6 mb-4 mb-lg-0\">\r\n            <div class=\"feature-1 border\">\r\n              <div class=\"icon-wrapper bg-primary\">\r\n                <span class=\"flaticon-school-material text-white\"></span>\r\n              </div>\r\n              <div class=\"feature-1-content\">\r\n                <h2>Programme de la formation</h2>\r\n                <p style=\"text-align: justify;\">La formation est répartie sur 4 semestres et chaque semestre est\r\n                  constitué de 4 modules <br> &nbsp; <br></p>\r\n                <p><a href=\"programme.php\" class=\"btn btn-primary px-4 rounded-0\">En savoir plus</a></p>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class=\"col-lg-4 col-md-6 mb-4 mb-lg-0\">\r\n            <div class=\"feature-1 border\">\r\n              <div class=\"icon-wrapper bg-primary\">\r\n                <span class=\"flaticon-library text-white\"></span>\r\n              </div>\r\n              <div class=\"feature-1-content\">\r\n                <h2>Objectifs de la formation</h2>\r\n                <p style=\"text-align: justify;\">L\'objectif de la formation est de former des développeurs web et mobiles\r\n                  (Bac +5) maîtrisant les technologies les plus recherchées sur le marché de l\'emploi, tant au niveau\r\n                  national qu\'international.</p>\r\n                <p><a href=\"apropos.php#objectifs\" class=\"btn btn-primary px-4 rounded-0\">En savoir plus</a></p>\r\n              </div>\r\n            </div>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n\r\n\r\n    <div class=\"site-section\">\r\n      <div class=\"container\">\r\n\r\n\r\n        <div class=\"row mb-5 justify-content-center text-center\">\r\n          <div class=\"col-lg-6 mb-5\">\r\n            <h2 class=\"section-title-underline mb-3\">\r\n              <span>Nos modules</span>\r\n            </h2>\r\n            <p>La formation est répartie sur 4 semestres et chaque semestre est constitué de 4 modules:</p>\r\n          </div>\r\n        </div>\r\n\r\n        <div class=\"row\">\r\n          <div class=\"col-12\">\r\n            <div class=\"owl-slide-3 owl-carousel\">\r\n\r\n              <?php foreach ($matieres as $matiere): ?>\r\n                <div class=\"course-1-item\">\r\n                  <figure class=\"thumnail\">\r\n                    <center><a href=\"programme.php\">\r\n                        <i class=\"<?php echo htmlspecialchars($matiere[\'photo\']); ?> fa-10x mb-5 mt-5\"></i>\r\n                        <!-- Icono de tamaño 4x -->\r\n                      </a></center>\r\n                    <div class=\"price mb-4\">SEMESTRE: <?php echo htmlspecialchars($matiere[\'semestre\']); ?></div>\r\n                    <div class=\"category\">\r\n                      <h3>MODULE: <?php echo htmlspecialchars($matiere[\'module\']); ?></h3>\r\n                    </div>\r\n                  </figure>\r\n                </div>\r\n              <?php endforeach; ?>\r\n            </div>\r\n          </div>\r\n        </div>\r\n\r\n\r\n\r\n      </div>\r\n    </div>\r\n\r\n\r\n\r\n\r\n    <div class=\"section-bg style-1\" style=\"background-image: url(\'images/about_1.jpg\');\">\r\n      <div class=\"container\">\r\n        <div class=\"row\">\r\n          <div class=\"col-lg-4\">\r\n            <h2 class=\"section-title-underline style-2\">\r\n              <span>À propos d\'IWA</span>\r\n            </h2>\r\n          </div>\r\n          <div class=\"col-lg-8\">\r\n            <p class=\"lead\" style=\"text-align: justify;\">La formation Ingénierie du Web Avancé (IWA) est préparée en\r\n              concertation avec plusieurs\r\n              sociétés nationales et multinationales pour répondre à un besoin de formation au domaine des technologies\r\n              web les plus demandées sur le marché du travail national et international. <br>\r\n\r\n              Durée de formation: 2 ans <br>\r\n\r\n              Méthode d’enseignement: en présentiel (Faculté des Sciences de Tétouan), et à distance à travers des\r\n              plateformes dédiées. <br>\r\n\r\n              Pour candidater à cette formation diplômante, vous êtes invité à remplir ce formulaire de pré-inscription\r\n              au plutard le 5 Novembre 2024. <br>\r\n\r\n              Frais de formation: 40 000 DH pour les 2 années. Payable en 2 tranches.</p>\r\n            <p><a href=\"apropos.php\">En savoir plus</a></p>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n\r\n    <!-- // 05 - Block -->\r\n    <div class=\"site-section\">\r\n      <div class=\"container\">\r\n        <div class=\"row mb-5\">\r\n          <div class=\"col-lg-4\">\r\n            <h2 class=\"section-title-underline\">\r\n              <span>Nos Partenaires</span>\r\n            </h2>\r\n          </div>\r\n        </div>\r\n\r\n\r\n        <div class=\"owl-slide owl-carousel\">\r\n\r\n          <div class=\"ftco-testimonial-1\">\r\n            <div class=\"ftco-testimonial-vcard d-flex align-items-center mb-4\">\r\n              <a href=\"#\">\r\n                <center></center>\r\n                <img src=\"img/logo-dxc.jpg\" alt=\"Image\" class=\"img-fluid mr-3 w-100 h-100\">\r\n                <h3>DXC Technology</h3>\r\n                </center>\r\n              </a>\r\n            </div>\r\n          </div>\r\n\r\n          <div class=\"ftco-testimonial-1\">\r\n            <div class=\"ftco-testimonial-vcard d-flex align-items-center mb-4\">\r\n              <a href=\"https://powertech-empire.com\">\r\n                <center>\r\n                  <img src=\"img/powertech-empire.png\" alt=\"Image\" class=\"img-fluid mr-3 w-100 h-100\">\r\n                  <h3>Powertech Empire</h3>\r\n                </center>\r\n              </a>\r\n            </div>\r\n          </div>\r\n\r\n          <div class=\"ftco-testimonial-1\">\r\n            <div class=\"ftco-testimonial-vcard d-flex align-items-center mb-4\">\r\n              <a href=\"https://www.fst.ac.ma/site/\">\r\n                <center>\r\n                  <img src=\"img/fs_tetouan.jpg\" alt=\"Image\" class=\"img-fluid mr-3 w-100 h-100\">\r\n                  <h3> Faculté des Sciences de Tétouan</h3>\r\n                </center>\r\n              </a>\r\n            </div>\r\n          </div>\r\n\r\n          <div class=\"ftco-testimonial-1\">\r\n            <div class=\"ftco-testimonial-vcard d-flex align-items-center mb-4\">\r\n              <a href=\"#\">\r\n                <center><img src=\"img/logo-servicenow.jpg\" alt=\"Image\" class=\"img-fluid mr-3 w-75 h-75\">\r\n                  <h3>Service Now</h3>\r\n                </center>\r\n              </a>\r\n            </div>\r\n          </div>\r\n\r\n        </div>\r\n\r\n      </div>\r\n    </div>\r\n\r\n    <div class=\"news-updates\">\r\n      <div class=\"container\">\r\n\r\n        <div class=\"row\" style=\"margin-bottom: 50px;\">\r\n          <div class=\"col-lg-2\">\r\n            <!-- Columna vacía -->\r\n          </div>\r\n          <div class=\"col-lg-8\">\r\n            <!-- Carrusel de iframes de YouTube -->\r\n            <div id=\"youtubeCarousel\" class=\"carousel slide\" data-ride=\"carousel\">\r\n              <!-- Indicadores del carrusel -->\r\n              <ol class=\"carousel-indicators\">\r\n                <li data-target=\"#youtubeCarousel\" data-slide-to=\"0\" class=\"active\"></li>\r\n                <li data-target=\"#youtubeCarousel\" data-slide-to=\"1\"></li>\r\n                <li data-target=\"#youtubeCarousel\" data-slide-to=\"2\"></li>\r\n              </ol>\r\n\r\n              <!-- Contenedor de los iframes -->\r\n              <div class=\"carousel-inner\">\r\n                <div class=\"carousel-item active\">\r\n                  <iframe width=\"100%\" height=\"400\" src=\"https://www.youtube.com/embed/hly--NnKeQA\"\r\n                    title=\"IWA - Naoual Touil, 1ère année du DCESS (Bac+5) Ingénierie du Web Avancé,  FS Tétouan\"\r\n                    frameborder=\"0\"\r\n                    allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\"\r\n                    referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n                </div>\r\n                <div class=\"carousel-item\">\r\n                  <iframe width=\"100%\" height=\"400\" src=\"https://www.youtube.com/embed/RYT09bs1JWI\"\r\n                    title=\"Workshop: From Learning to Earning - Developers&#39; path (FLE) - FS Tetuan - July 1st, 2024\"\r\n                    frameborder=\"0\"\r\n                    allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\"\r\n                    referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n                </div>\r\n                <div class=\"carousel-item\">\r\n                  <iframe width=\"100%\" height=\"400\" src=\"https://www.youtube.com/embed/zxB38fZeQrM\"\r\n                    title=\"Make Your Own Startup - From Idea to Launch\" frameborder=\"0\"\r\n                    allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\"\r\n                    referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>\r\n                </div>\r\n              </div>\r\n\r\n              <!-- Controles del carrusel -->\r\n              <a class=\"carousel-control-prev\" href=\"#youtubeCarousel\" role=\"button\" data-slide=\"prev\">\r\n                <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>\r\n                <span class=\"sr-only\">Ancien</span>\r\n              </a>\r\n              <a class=\"carousel-control-next\" href=\"#youtubeCarousel\" role=\"button\" data-slide=\"next\">\r\n                <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>\r\n                <span class=\"sr-only\">Suivant</span>\r\n              </a>\r\n            </div>\r\n          </div>\r\n          <div class=\"col-lg-2\">\r\n            <!-- Columna vacía -->\r\n          </div>\r\n        </div>\r\n      </div>\r\n\r\n      <div class=\"footer\">\r\n        <div class=\"container\">\r\n          <div class=\"row\">\r\n            <div class=\"col-lg-3\">\r\n              <p class=\"mb-4\"><img src=\"img/logo_2.png\" alt=\"Image\" class=\"img-fluid\"></p>\r\n              <p style=\"text-align: justify;\">La formation Ingénierie du Web Avancé (IWA) est préparée en concertation\r\n                avec plusieurs sociétés\r\n                nationales et multinationales pour répondre à un besoin de formation au domaine des technologies web les\r\n                plus demandées sur le marché du travail national et international.</p>\r\n              <p><a href=\"apropos.php\">En savoir plus</a></p>\r\n            </div>\r\n            <div class=\"col-lg-3\">\r\n              <h3 class=\"footer-heading\"><span>Formation iWA</span></h3>\r\n              <ul class=\"list-unstyled\">\r\n                <li><a href=\"index.php\">Accueil</a></li>\r\n                <li><a href=\"apropos.php\">À propos de IWA</a></li>\r\n                <li><a href=\"professeurs.php\">Nos professeurs</a></li>\r\n                <li><a href=\"programme.php\">Nos modules</a></li>\r\n              </ul>\r\n            </div>\r\n            <div class=\"col-lg-3\">\r\n              <h3 class=\"footer-heading\"><span>Espace utilisateurs IWA</span></h3>\r\n              <ul class=\"list-unstyled\">\r\n                <li><a href=\"etu.php\">Espace étudiant IWA</a></li>\r\n                <li><a href=\"ens.php\">Espace ensegniants IWA</a></li>\r\n                <li><a href=\"admin.php\">Espace administrateur IWA</a></li>\r\n              </ul>\r\n            </div>\r\n            <div class=\"col-lg-3\">\r\n              <h3 class=\"footer-heading\"><span>Autres</span></h3>\r\n              <ul class=\"list-unstyled\">\r\n                <li><a href=\"contact.php\">Nous Contacter</a></li>\r\n                <li><a href=\"support_des_cours.php\">Support des Cours</a></li>\r\n                <li><a href=\"evenements.php\">Événements</a></li>\r\n                <li><a href=\"#\">Nos Partenaires</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n\r\n          <div class=\"row\">\r\n            <div class=\"col-12\">\r\n              <div class=\"copyright\">\r\n                <p>\r\n                  <!-- Link back to Colorlib can\'t be removed. Template is licensed under CC BY 3.0. -->\r\n                  Copyright &copy;\r\n                  <script>document.write(new Date().getFullYear());</script>&nbsp;<a href=\"index.php\">IWA</a> All rights\r\n                  reserved</a>\r\n                  <!-- Link back to Colorlib can\'t be removed. Template is licensed under CC BY 3.0. -->\r\n                </p>\r\n              </div>\r\n            </div>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n    <!-- .site-wrap -->\r\n\r\n\r\n    <!-- loader -->\r\n    <div id=\"loader\" class=\"show fullscreen\"><svg class=\"circular\" width=\"48px\" height=\"48px\">\r\n        <circle class=\"path-bg\" cx=\"24\" cy=\"24\" r=\"22\" fill=\"none\" stroke-width=\"4\" stroke=\"#eeeeee\" />\r\n        <circle class=\"path\" cx=\"24\" cy=\"24\" r=\"22\" fill=\"none\" stroke-width=\"4\" stroke-miterlimit=\"10\"\r\n          stroke=\"#51be78\" />\r\n      </svg></div>\r\n\r\n    <script src=\"./js/jquery-3.3.1.min.js\"></script>\r\n    <script src=\"./js/jquery-migrate-3.0.1.min.js\"></script>\r\n    <script src=\"./js/jquery-ui.js\"></script>\r\n    <script src=\"./js/popper.min.js\"></script>\r\n    <script src=\"./js/bootstrap.min.js\"></script>\r\n    <script src=\"./js/owl.carousel.min.js\"></script>\r\n    <script src=\"./js/jquery.stellar.min.js\"></script>\r\n    <script src=\"./js/jquery.countdown.min.js\"></script>\r\n    <script src=\"./js/bootstrap-datepicker.min.js\"></script>\r\n    <script src=\"./js/jquery.easing.1.3.js\"></script>\r\n    <script src=\"./js/aos.js\"></script>\r\n    <script src=\"./js/jquery.fancybox.min.js\"></script>\r\n    <script src=\"./js/jquery.sticky.js\"></script>\r\n    <script src=\"./js/jquery.mb.YTPlayer.min.js\"></script>\r\n\r\n\r\n\r\n\r\n    <script src=\"./js/main.js\"></script>\r\n\r\n</body>\r\n\r\n</html>', NULL, 0),
(5, 4, 'Python', 'from django.db import models\r\n\r\nclass Category(models.Model):\r\n    name = models.CharField(max_length=255, unique=True)\r\n    image = models.ImageField(upload_to=\'categories/\', blank=True, null=True)\r\n\r\n    def __str__(self):\r\n        return self.name', NULL, 0),
(6, 2, 'Java Scripts', 'import api from \"./api\";\n\nconst filterService = {\n  getCategories: async () => {\n    const response = await api.get(\"/categories\");\n    return response.data;\n  },\n\n  getBrands: async () => {\n    const response = await api.get(\"/brands\");\n    return response.data;\n  },\n\n  getMaxAndMinPrice: async () => {\n    const response = await api.get(\"/products/price-range\");\n    return response.data;\n  },\n\n  filterByPriceRange: async (min, max) => {\n    const response = await api.get(\"/products/by-price-range\", { params: { min, max } });\n    return response.data;\n  },\n\n  filterByCategory: async (categoryId) => {\n    const response = await api.get(`/products/${categoryId}/by-category`);\n    return response.data;\n  },\n\n  filterByBrand: async (brandId) => {\n    const response = await api.get(`/products/${brandId}/by-brand`);\n    return response.data;\n  },\n\n  filterByRatingValue: async (rating) => {\n    const response = await api.get(\"/products/by-rating\", { params: { rating } });\n    return response.data;\n  }\n};\n\nexport default filterService;\nimport api from \"./api\";\n\nconst filterService = {\n  getCategories: async () => {\n    const response = await api.get(\"/categories\");\n    return response.data;\n  },\n\n  getBrands: async () => {\n    const response = await api.get(\"/brands\");\n    return response.data;\n  },\n\n  getMaxAndMinPrice: async () => {\n    const response = await api.get(\"/products/price-range\");\n    return response.data;\n  },\n\n  filterByPriceRange: async (min, max) => {\n    const response = await api.get(\"/products/by-price-range\", { params: { min, max } });\n    return response.data;\n  },\n\n  filterByCategory: async (categoryId) => {\n    const response = await api.get(`/products/${categoryId}/by-category`);\n    return response.data;\n  },\n\n  filterByBrand: async (brandId) => {\n    const response = await api.get(`/products/${brandId}/by-brand`);\n    return response.data;\n  },\n\n  filterByRatingValue: async (rating) => {\n    const response = await api.get(\"/products/by-rating\", { params: { rating } });\n    return response.data;\n  }\n};\n\nexport default filterService;\n', NULL, 0),
(7, 3, 'HTML', '<!DOCTYPE html>\r\n<html lang=\"es\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>iChat</title>\r\n    <link rel=\"stylesheet\" href=\"styles.css\">\r\n</head>\r\n<body>\r\n    <div class=\"chat-container\">\r\n        <header class=\"chat-header\">\r\n            <h1>iChat</h1>\r\n            <div class=\"status-indicator\" id=\"statusIndicator\"></div>\r\n        </header>\r\n        \r\n        <div class=\"chat-messages\" id=\"chatMessages\">\r\n            <div class=\"message bot-message\">\r\n                <div class=\"message-content\">\r\n                    Hi! I\'m a chatbot powered by Gemini. How can I help you?\r\n                </div>\r\n                <div class=\"message-time\" id=\"initialTime\"></div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"chat-input-container\">\r\n            <textarea \r\n                id=\"userInput\" \r\n                placeholder=\"Write your message here...\" \r\n                rows=\"1\"\r\n            ></textarea>\r\n            <button id=\"sendButton\">\r\n                <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                    <path d=\"M22 2L11 13\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\r\n                    <path d=\"M22 2L15 22L11 13L2 9L22 2Z\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\r\n                </svg>\r\n            </button>\r\n        </div>\r\n        \r\n        <div class=\"chat-footer\">\r\n            <small>Powered by Gemini AI</small>\r\n        </div>\r\n    </div>\r\n\r\n    <script src=\"script.js\"></script>\r\n</body>\r\n</html>', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `diplomas`
--

CREATE TABLE `diplomas` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `issuing_organization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_year` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_year` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diplomas`
--

INSERT INTO `diplomas` (`id`, `name`, `issuing_organization`, `first_year`, `last_year`, `display_order`) VALUES
(1, 'Diploma from the Advanced Web Engineering (IWA) program – Bac+5 level', 'Faculté des Sciences de Tétouan', '2024', 'Présent', 1),
(2, 'Bachelor of Science in Mathematics and Computer Science (SMI) – Bac+3', 'Faculté des Sciences de Tétouan', '2021', '2024', 2),
(3, 'Bac+2 in Metallurgical Engineering', 'Université Nationale de Guinée Équatoriale (UNGE)', '2019', '2021', 3),
(4, 'Scientific Baccalaureate, specializing in Physics and Biology', 'École Adventiste de Malabo', '2018', '2019', 4);

-- --------------------------------------------------------

--
-- Table structure for table `key_achievements`
--

CREATE TABLE `key_achievements` (
  `id` int NOT NULL,
  `achievement` text COLLATE utf8mb4_general_ci NOT NULL,
  `impact_description` text COLLATE utf8mb4_general_ci,
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `key_achievements`
--

INSERT INTO `key_achievements` (`id`, `achievement`, `impact_description`, `display_order`, `is_active`) VALUES
(1, 'Clinical management project management', 'I developed a management system for a dental clinic that improved the efficiency of patient and appointment management by 40%.', 1, 1),
(2, 'Implementation of personalized e-commerce', 'I developed an e-commerce platform tailored to the client\'s specific needs, resulting in a 25% increase in their online sales.', 2, 1),
(3, 'Process optimization with Docker', 'Containerizing legacy applications reduces infrastructure costs by 30% and improves portability.', 3, 1),
(4, 'Library management system', 'Creation of a Java application for library management that reduced loan/consultation time by 50%.', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `proficiency` enum('basic','intermediate','advanced','native') COLLATE utf8mb4_general_ci NOT NULL,
  `certified_level` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `certificate_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `proficiency`, `certified_level`, `certificate_file`, `display_order`) VALUES
(4, 'Español', 'native', NULL, NULL, 1),
(5, 'Francés', 'advanced', NULL, NULL, 2),
(6, 'Inglés', 'intermediate', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `personal_info`
--

CREATE TABLE `personal_info` (
  `id` int NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `job_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `short_bio` text COLLATE utf8mb4_general_ci,
  `long_bio` text COLLATE utf8mb4_general_ci,
  `profile_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `resume_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `availability_status` enum('open','busy','unavailable') COLLATE utf8mb4_general_ci DEFAULT 'open',
  `professional_summary` text COLLATE utf8mb4_general_ci,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_info`
--

INSERT INTO `personal_info` (`id`, `full_name`, `job_title`, `short_bio`, `long_bio`, `profile_image`, `email`, `phone`, `location`, `resume_file`, `availability_status`, `professional_summary`, `updated_at`) VALUES
(1, 'Rafael Elebiyo Medina', 'Full-Stack Developer · Software Engineer', 'Desarrollador Full-Stack con experiencia en React, Spring Boot, FastAPI y Python. Apasionado por la Inteligencia Artificial y sus aplicaciones en el desarrollo web, especializado en soluciones escalables que generan valor real.', 'Ingeniero de software especializado en desarrollo Full-Stack, cursando el Máster Especializado en Ingeniería Web Avanzada (IWA) en la Facultad de Ciencias de Tetuán (Universidad Abdelmalek Essaâdi, 2024-2026). Combino experiencia en frontend (React, React Native, TypeScript, Tailwind CSS) y backend (Spring Boot, FastAPI, Django, Symfony) con un fuerte interés en agentes de IA y modelos de lenguaje (LangChain, LangGraph, Claude API, RAG). He liderado y participado en proyectos como Página 18:30, CENDER (fintech, CTO y cofundador), RilyPark Admin Dashboard, Mentora Studios y VALE Marketplace, aplicando arquitecturas escalables, Docker y pipelines de CI/CD con GitHub Actions. Miembro activo del club de IA e IoT de la FS Tetuán, con formación certificada en Azure, ciberseguridad, ciencia de datos y desarrollo de IA.', 'assets\\img\\3.jpeg', 'rafaelelebiyomedina1@gmail.com', '+212 691 795234', 'Tetuán, Marruecos', NULL, 'open', 'Desarrollador Full-Stack especializado en React, Spring Boot, FastAPI y soluciones impulsadas por IA.', '2026-07-07 02:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `professional_goals`
--

CREATE TABLE `professional_goals` (
  `id` int NOT NULL,
  `goal` text COLLATE utf8mb4_general_ci NOT NULL,
  `target_date` date DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professional_goals`
--

INSERT INTO `professional_goals` (`id`, `goal`, `target_date`, `is_completed`, `display_order`) VALUES
(1, 'Complete the Master\'s Degree in Advanced Web Engineering', '2026-06-01', 0, 1),
(2, 'Get certified in Cloud Architecture (AWS/Azure)', '2025-12-01', 1, 2),
(3, 'Develop an open-source project with 100+ stars on GitHub', '2025-06-01', 0, 3),
(4, 'Mastering advanced software design patterns', '2024-12-01', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `professional_references`
--

CREATE TABLE `professional_references` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `relationship` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `full_description` text COLLATE utf8mb4_general_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video_thumbnail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `project_date` date DEFAULT NULL,
  `client_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `project_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `category` enum('web','mobile','cross-platform','cms','cloud','other') COLLATE utf8mb4_general_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `popularity` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `short_description`, `full_description`, `cover_image`, `video_url`, `video_thumbnail`, `project_date`, `client_name`, `project_url`, `github_url`, `is_featured`, `category`, `display_order`, `popularity`, `created_at`) VALUES
(1, 'Denticare', 'Dental-management-system', 'Plataforma integral para administración de clínicas odontológicas.', 'A complete web-based solution for managing patients, appointments, medical records, and billing. Developed with Java Spring Boot on the backend and React on the frontend, using a MySQL database. Includes modules for statistical reports and PDF document generation.', 'assets\\img\\denticare.jpeg', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2024-07-01', 'Universidad Abdelmalek Essadi', '#', 'https://github.com/RafaelElebiyo/denticare_webapp', 1, 'web', 0, 0, '2025-07-22 01:17:28'),
(2, 'Shopzy', 'Ecommerce-platform', 'Customized online store with administration panel.', 'E-commerce platform developed with React, Spring, and Node.js that includes product management, a shopping cart, payment gateways (Stripe, PayPal), and a recommendation system based on user behavior. Optimized for SEO and performance.', 'assets\\img\\1.jpg', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2024-07-01', 'Personal Project', '#', 'https://github.com/RafaelElebiyo/shopzy', 1, 'cross-platform', 1, 0, '2025-07-22 01:17:28'),
(3, 'IChat', 'Custom AI Chatbot with Gemini API Integration', 'Artificial intelligence chatbot with integrated Gemini', 'This project is a personal implementation of an AI chatbot that uses Google\'s Gemini models as its inference engine. I developed the entire application, including the backend and frontend, to interact securely and dynamically with the Google AI Studio API.', 'assets\\img\\chat.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2025-11-16', 'Personal Project', '#', '#', 0, 'web', 4, 2, '2025-11-16 15:25:13'),
(4, 'BreazyBuy', 'Ecommerce web application', 'Modern and efficient online store', 'A modern and efficient online store, developed with Django and SQLite. This project represents a robust and elegant ecommerce solution, designed to offer a seamless user experience and uncomplicated backend management.', 'assets\\img\\breazy.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2024-09-03', 'Universidad Abdelmalek Essadi', '#', 'https://github.com/RafaelElebiyo/breezybuy_ecommerce.git', 0, 'other', 6, 0, '2025-11-16 15:25:13'),
(5, 'ClimaZone', 'Webapp for weather forecas', 'Platform for forecasting and studying climatic conditions of a specific location', 'A dynamic and interactive platform that empowers users to unlock deep insights into the climate of any location on Earth. By leveraging robust data models and a clear, user-friendly interface, it provides accurate forecasts and facilitates the study of environmental patterns, serving as a vital tool for planning, research, and education in an era of climate awareness.', 'assets\\img\\climazone.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2025-10-10', 'Personal Project', '#', 'https://github.com/RafaelElebiyo/climazone', 1, 'web', 2, 5, '2025-11-16 14:59:51'),
(6, 'Xamen Generator', 'Platform for creating exams', 'Platform for creating exams with automatic grading', 'Allows educators to create customized assessments with various question types.\nFeatures instant grading and detailed performance analytics.\nBuilt with Angular for a dynamic frontend experience.\nUses Spring Framework for secure and robust backend operations.\nMySQL database ensures reliable data storage and scalability.', 'assets\\img\\xamen.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2025-04-01', 'Universidad Abdelmalek Essadi', '#', 'https://github.com/RafaelElebiyo/Xamen_Generator', 1, 'web', 3, 4, '2025-11-16 15:14:02'),
(7, 'IWA Shop', 'Ecommerce whit Wordpress', 'Customized online store with administration panel.', 'I developed and deployed a customized online store with a full administration panel. The store is hosted on a standard web server, using cPanel for database setup and application deployment. It\'s a turnkey solution for managing products, orders, and customers efficiently.', 'assets\\img\\iwashop.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2025-01-01', 'Universidad Abdelmalek Essadi', 'https://rafael-elebiyo-medina.com/iwaShop', '#', 0, 'cms', 7, 0, '2025-11-16 16:01:33'),
(8, 'IWA Website', 'FS Tetouan Advanced Web Engineering website', 'Redesign of the IWA website of FS Tetouan', ' led the full-stack redesign of the FS Tetouan IWA department website, transforming it from a static page into a dynamic academic portal. Developed with PHP, MySQL, CSS, and JavaScript, the new platform features dedicated spaces for students (notes, schedule), teachers (resource management), and a comprehensive admin panel, centralizing departmental communication and resources into a single, modern interface.', 'assets\\img\\iwa.png', 'https://drive.google.com/file/d/1vKVdd2G_rgQpMeUIC46tIXoB7uMu19YC/view?usp=drive_link', 'assets/img/projects/video-thumbnail.jpg', '2025-01-01', 'Universidad Abdelmalek Essadi', 'https://www.rafael-elebiyo-medina.com/iwa', 'https://github.com/RafaelElebiyo/iwa_website.git', 0, 'web', 5, 0, '2025-11-16 16:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `project_features`
--

CREATE TABLE `project_features` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `feature` text COLLATE utf8mb4_general_ci NOT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_features`
--

INSERT INTO `project_features` (`id`, `project_id`, `feature`, `display_order`) VALUES
(1, 1, 'Comprehensive management of patients with complete medical history', 1),
(2, 1, 'Appointment system with automatic reminders via integrated chat', 2),
(3, 1, 'Generation of invoices and financial reports', 3),
(4, 2, 'Product catalog with advanced search and filters', 1),
(5, 2, 'Shopping cart with persistence between sessions', 2),
(6, 2, 'Administration panel with sales analytics', 3),
(12, 4, 'Administration panel with sales analytics', 0),
(13, 4, 'Shopping cart with persistence between sessions', 1),
(14, 4, 'Product catalog with advanced search and filters', 2),
(15, 2, 'Payment method integrated with Stripe', 4),
(16, 5, 'Obtaining hourly updated weather data.', 0),
(17, 5, 'Detailed information on temperature, humidity, wind speed, atmospheric pressure and visibility.', 1),
(18, 5, 'Data on maximum and minimum temperatures, general conditions and probability of rain.', 2),
(19, 5, 'Access to historical data to compare current conditions with previous years.', 3),
(20, 7, 'Shopping cart with persistence between sessions', 0),
(21, 7, 'Product catalog with advanced search and filters', 1),
(22, 7, 'Payment method integrated with Stripe', 2),
(23, 7, 'Administration panel with sales analytics', 3),
(28, 8, 'Administration panel for managing grades, modules, teachers, and students', 0),
(29, 8, 'Student space with access to grades, transcripts, and schedules', 1),
(30, 8, 'Space for teachers to manage student grades', 2),
(31, 8, 'Visitor space to display details about IWA', 3),
(32, 3, 'Advanced natural language processing using Google\'s Gemini models', 0),
(33, 3, 'Secure API integration with Google AI Studio', 1),
(34, 3, 'Efficient API key management and secure credential handling', 2),
(35, 3, 'Session management and conversation history', 3),
(36, 6, 'Instant evaluation and scoring for various question types, providing immediate feedback.', 0),
(37, 6, 'Intuitive tools for designing tailored assessments with multiple question formats.', 1),
(38, 6, 'Built with Angular, Spring Framework, and MySQL for reliability and scalability.', 2),
(39, 6, 'Multi-level user permissions ensuring data protection for administrators, teachers, and students.', 3);

-- --------------------------------------------------------

--
-- Table structure for table `project_technologies`
--

CREATE TABLE `project_technologies` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `technology` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_technologies`
--

INSERT INTO `project_technologies` (`id`, `project_id`, `technology`, `display_order`) VALUES
(1, 1, 'PHP', 1),
(2, 1, 'MySQL', 2),
(3, 1, 'JavaScript', 3),
(4, 1, 'Bootstrap', 4),
(5, 2, 'React', 1),
(6, 2, 'Node.js', 2),
(7, 2, 'MySQL', 3),
(8, 5, 'React JS', 0),
(9, 5, 'Bootstrap & CSS', 3),
(10, 5, 'Java Scripts', 0),
(11, 2, 'Spring', 0),
(12, 2, 'Docker & Docker compose', 3),
(13, 5, 'Docker & Docker compose', 3),
(14, 3, 'HTML', 0),
(15, 3, 'CSS', 1),
(16, 3, 'Java Scripts', 2),
(17, 3, 'Gemini API Key', 3),
(18, 6, 'Angular JS', 0),
(19, 6, 'Spring', 1),
(20, 6, 'MySQL', 2),
(26, 8, 'PHP', 0),
(27, 8, 'HTML', 1),
(28, 8, 'CSS', 2),
(29, 8, 'Java Script', 3),
(30, 8, 'MySQL', 4),
(31, 7, 'WordPress', 0),
(32, 7, 'Elemento', 1),
(33, 4, 'Django', 0),
(34, 4, 'Bootstrap & CSS', 1),
(35, 4, 'SQLite', 2),
(36, 4, 'Ajax', 3);

-- --------------------------------------------------------

--
-- Table structure for table `project_testimonials`
--

CREATE TABLE `project_testimonials` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `client_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `testimonial` text COLLATE utf8mb4_general_ci NOT NULL,
  `client_position` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_company` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technical_skills`
--

CREATE TABLE `technical_skills` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `category` enum('frontend','backend','mobile','design','devops','database','other') COLLATE utf8mb4_general_ci NOT NULL,
  `proficiency` tinyint DEFAULT NULL,
  `years_of_experience` decimal(3,1) DEFAULT NULL,
  `last_used` date DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `display_order` int DEFAULT '0'
) ;

--
-- Dumping data for table `technical_skills`
--

INSERT INTO `technical_skills` (`id`, `name`, `category`, `proficiency`, `years_of_experience`, `last_used`, `icon`, `is_featured`, `display_order`) VALUES
(10, 'React', 'frontend', 90, 2.0, NULL, NULL, 0, 1),
(11, 'React Native', 'frontend', 70, 1.0, NULL, NULL, 0, 2),
(12, 'TypeScript', 'frontend', 85, 2.0, NULL, NULL, 0, 3),
(13, 'AngularJS', 'frontend', 60, 1.0, NULL, NULL, 0, 4),
(14, 'Tailwind CSS', 'frontend', 85, 2.0, NULL, NULL, 0, 5),
(15, 'Spring Boot', 'backend', 88, 2.0, NULL, NULL, 0, 6),
(16, 'FastAPI', 'backend', 85, 2.0, NULL, NULL, 0, 7),
(17, 'Django', 'backend', 75, 1.0, NULL, NULL, 0, 8),
(18, 'Symfony', 'backend', 60, 1.0, NULL, NULL, 0, 9),
(19, 'MySQL', 'database', 85, 2.0, NULL, NULL, 0, 10),
(20, 'PostgreSQL', 'database', 80, 2.0, NULL, NULL, 0, 11),
(21, 'MongoDB', 'database', 75, 1.0, NULL, NULL, 0, 12),
(22, 'Qdrant', 'database', 75, 1.0, NULL, NULL, 0, 13),
(23, 'ChromaDB', 'database', 70, 1.0, NULL, NULL, 0, 14),
(24, 'Docker', 'devops', 85, 2.0, NULL, NULL, 0, 15),
(25, 'GitHub Actions CI/CD', 'devops', 80, 2.0, NULL, NULL, 0, 16),
(26, 'Azure', 'devops', 65, 1.0, NULL, NULL, 0, 17),
(27, 'Linux/Bash', 'devops', 80, 2.0, NULL, NULL, 0, 18),
(28, 'Python', 'other', 90, 2.0, NULL, NULL, 0, 19),
(29, 'JavaScript', 'other', 88, 2.0, NULL, NULL, 0, 20),
(30, 'Java', 'other', 80, 2.0, NULL, NULL, 0, 21),
(31, 'PHP', 'other', 82, 2.0, NULL, NULL, 0, 22),
(32, 'LangChain / LangGraph', 'other', 85, 1.0, NULL, NULL, 0, 23),
(33, 'Claude API / Prompt Engineering', 'other', 85, 1.0, NULL, NULL, 0, 24),
(34, 'Ollama / RAG', 'other', 80, 1.0, NULL, NULL, 0, 25);

-- --------------------------------------------------------

--
-- Table structure for table `technical_tools`
--

CREATE TABLE `technical_tools` (
  `id` int NOT NULL,
  `skill_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `proficiency` tinyint DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `work_achievements`
--

CREATE TABLE `work_achievements` (
  `id` int NOT NULL,
  `work_id` int NOT NULL,
  `achievement` text COLLATE utf8mb4_general_ci NOT NULL,
  `impact_description` text COLLATE utf8mb4_general_ci,
  `is_quantifiable` tinyint(1) DEFAULT '0',
  `metric_value` decimal(10,2) DEFAULT NULL,
  `metric_unit` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_achievements`
--

INSERT INTO `work_achievements` (`id`, `work_id`, `achievement`, `impact_description`, `is_quantifiable`, `metric_value`, `metric_unit`, `display_order`) VALUES
(5, 4, 'Pipeline multi-agente con tasa de validación del 92% y tiempo medio de generación de 187s', NULL, 0, NULL, NULL, 1),
(6, 4, 'Biblioteca de más de 200 componentes reutilizables integrados en Mentora Studio', NULL, 0, NULL, NULL, 2),
(7, 5, 'Implementación de autenticación JWT y control de acceso por roles (SuperAdmin/Manager)', NULL, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `work_experience`
--

CREATE TABLE `work_experience` (
  `id` int NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `company` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT '0',
  `employment_type` enum('full-time','part-time','contract','freelance','internship') COLLATE utf8mb4_general_ci DEFAULT 'full-time',
  `company_logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_experience`
--

INSERT INTO `work_experience` (`id`, `position`, `company`, `location`, `description`, `start_date`, `end_date`, `is_current`, `employment_type`, `company_logo`, `display_order`) VALUES
(4, 'Backend Developer', 'HB Développement', 'Tetuán, Marruecos', 'Desarrollo del microservicio de ingesta de datos de Mentora Studio: pipelines multi-agente con LangGraph, Qdrant y RabbitMQ para generación automática de laboratorios interactivos.', '2026-02-01', '2026-06-30', 0, '', NULL, 1),
(5, 'Frontend Developer', 'SB Solutions MA', 'Remoto', 'Desarrollo del panel de administración de RilyPark (React + TypeScript) con control de acceso basado en roles para SuperAdmin y Manager, a partir de diseños en Figma.', '2026-02-01', '2026-05-31', 0, '', NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_samples`
--
ALTER TABLE `code_samples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `diplomas`
--
ALTER TABLE `diplomas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `key_achievements`
--
ALTER TABLE `key_achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_info`
--
ALTER TABLE `personal_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professional_goals`
--
ALTER TABLE `professional_goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professional_references`
--
ALTER TABLE `professional_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `project_features`
--
ALTER TABLE `project_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_technologies`
--
ALTER TABLE `project_technologies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_testimonials`
--
ALTER TABLE `project_testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `technical_skills`
--
ALTER TABLE `technical_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technical_tools`
--
ALTER TABLE `technical_tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `work_achievements`
--
ALTER TABLE `work_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_id` (`work_id`);

--
-- Indexes for table `work_experience`
--
ALTER TABLE `work_experience`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `code_samples`
--
ALTER TABLE `code_samples`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `diplomas`
--
ALTER TABLE `diplomas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `key_achievements`
--
ALTER TABLE `key_achievements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_info`
--
ALTER TABLE `personal_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `professional_goals`
--
ALTER TABLE `professional_goals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `professional_references`
--
ALTER TABLE `professional_references`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `project_features`
--
ALTER TABLE `project_features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `project_technologies`
--
ALTER TABLE `project_technologies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `project_testimonials`
--
ALTER TABLE `project_testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technical_skills`
--
ALTER TABLE `technical_skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technical_tools`
--
ALTER TABLE `technical_tools`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_achievements`
--
ALTER TABLE `work_achievements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `work_experience`
--
ALTER TABLE `work_experience`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `code_samples`
--
ALTER TABLE `code_samples`
  ADD CONSTRAINT `code_samples_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_features`
--
ALTER TABLE `project_features`
  ADD CONSTRAINT `project_features_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_technologies`
--
ALTER TABLE `project_technologies`
  ADD CONSTRAINT `project_technologies_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_testimonials`
--
ALTER TABLE `project_testimonials`
  ADD CONSTRAINT `project_testimonials_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `technical_tools`
--
ALTER TABLE `technical_tools`
  ADD CONSTRAINT `technical_tools_ibfk_1` FOREIGN KEY (`skill_id`) REFERENCES `technical_skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_achievements`
--
ALTER TABLE `work_achievements`
  ADD CONSTRAINT `work_achievements_ibfk_1` FOREIGN KEY (`work_id`) REFERENCES `work_experience` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
