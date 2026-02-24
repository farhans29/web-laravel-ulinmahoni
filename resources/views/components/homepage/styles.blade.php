<style>


/* Base styles */
    :root {
      --teal-600: #0d9488;
      --teal-700: #0f766e;
      --red-500: #ef4444;
      --red-700: #b91c1c;
    }
    
    body {
      background: linear-gradient(to bottom, #f8f7f4 0%, #f8f7f4 30%, #e8dcc8 100%);
      min-height: 100vh;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    /* Hero styles */
    .hero-section {
      position: relative;
      width: 100%;
      height: 600px;
      background-color: #111827;
    }

    @media (min-width: 768px) {
      .hero-section {
        height: 400px;
      }
    }

    @media (min-width: 1024px) {
      .hero-section {
        height: 600px;
      }
    }

    .hero-content {
      position: relative;
      width: 100%;
      height: 100%;
    }

    .hero-media {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .search-section {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      transform: translateY(50%);
      z-index: 30;
    }

    .search-container {
      max-width: 80rem;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .search-box {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(8px);
      border-radius: 1rem;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      padding: 1.5rem;
    }

    /* Add margin to compensate for search box overlap */
    main > section:first-of-type {
      margin-top: 4rem;
    }

/* Global styles */
.bg-teal-600 { background-color: var(--teal-600); }
.bg-teal-700 { background-color: var(--teal-700); }
.text-teal-600 { color: var(--teal-600); }
.hover\:bg-teal-700:hover { background-color: var(--teal-700); }
.bg-red-500 { background-color: var(--red-500); }
.bg-red-700 { background-color: var(--red-700); }

/* Component styles */
.property-card {
    transition: transform 0.3s ease;
}

.property-card:hover .card-image {
    transform: scale(1.05);
}

.card-image {
    transition: transform 0.5s ease;
}

.tab-trigger {
    position: relative;
}

.tab-trigger.active {
    border-bottom: 2px solid var(--teal-600);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.gradient-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
}

.feature-icon {
    width: 40px;
    height: 40px;
    background-color: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.25rem;
}

/* Section styles */
.section-light {
    background-color: transparent;
    padding: 4rem 0;
}

.section-dark {
    background-color: transparent;
    padding: 4rem 0;
}

.section-container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;  /* Changed from 1.5rem to match search bar */
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title h2 {
    font-size: 2.25rem;
    font-weight: 300;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.section-title .divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.section-title .divider-line {
    width: 3rem;
    height: 1px;
    background-color: #d1d5db;
}

.section-title .divider-text {
    color: #0d9488;
    font-style: italic;
} 
</style>