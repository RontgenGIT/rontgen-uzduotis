<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        color: #444;
        line-height: 1.8;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .header {
        background: rgba(120, 102, 234, 0.9);
        backdrop-filter: blur(10px);
        padding: 1rem 2rem;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo img {
        height: 40px;
        width: auto;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-link {
        background: #667eea;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background: #5a67d8;
        transform: translateY(-1px);
    }

    .container {
        width: 100%;
        margin: 20px auto;
        padding: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        min-height: 0;
    }

    .page-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .page-header img {
        width: 120px;
        margin-bottom: 25px;
    }

    .content {
        text-align: center;
        max-width: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .content p {
        text-align: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #0056b3;
        font-weight: 600;
    }

    .content h2 {
        color: #0056b3;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-top: 30px;
    }

    .content ul {
        list-style-type: none;
        padding-left: 0;
    }

    .content li {
        margin-bottom: 15px;
        padding-left: 25px;
        position: relative;
    }

    .content li:before {
        content: '\2713';
        color: #28a745;
        position: absolute;
        left: 0;
    }
</style>
<div class="page-header">
    <img src="https://rontgen.lt/lending/img/logo_white.png" alt="Rontgen Logo">
    <h1><?= __('home.welcome') ?></h1>
</div>

<div class="content">
    <p><?= __('home.intro') ?></p>

    <h2><?= __('home.tasks.title') ?></h2>
    <ul>
        <li><strong><?= __('home.tasks.task1.title') ?>:</strong> <?= __('home.tasks.task1.description') ?></li>
        <li><strong><?= __('home.tasks.task2.title') ?>:</strong> <?= __('home.tasks.task2.description') ?></li>
        <li><strong><?= __('home.tasks.task3.title') ?>:</strong> <?= __('home.tasks.task3.description') ?></li>
        <li><strong><?= __('home.tasks.task4.title') ?>:</strong> <?= __('home.tasks.task4.description') ?></li>
    </ul>
</div>
