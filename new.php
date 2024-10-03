<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creative Oasis</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #F8EBD5;
      color: #231F20;
    }
    .navbar {
      background-color: rgba(0, 0, 0, 0.7);
    }
    .navbar-brand {
      font-size: 28px;
      font-weight: bold;
    }
    .navbar-nav .nav-link {
      font-size: 20px;
    }
    .jumbotron {
      background-image: url('https://via.placeholder.com/1500x500');
      background-size: cover;
      background-position: center;
      color: #F8EBD5;
      text-align: center;
      padding: 100px 0;
      margin-bottom: 0;
    }
    .jumbotron h1 {
      font-size: 48px;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    .card {
      background-color: rgba(0, 0, 0, 0.8);
      color: #F8EBD5;
      border: none;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.05);
    }
    .card-title {
      font-size: 24px;
      font-weight: bold;
    }
    .card-text {
      font-size: 18px;
    }
    .footer {
      background-color: rgba(0, 0, 0, 0.7);
      color: #F8EBD5;
      padding: 20px 0;
      text-align: center;
    }
    .footer p {
      font-size: 20px;
      font-style: italic;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Creative Oasis</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="jumbotron">
    <div class="container">
      <h1>Welcome to Creative Oasis</h1>
      <p class="lead">Unleash your imagination and explore boundless creativity</p>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">Creative Inspiration</h5>
            <p class="card-text">Let your ideas soar to new heights and unlock your potential.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">Imaginative Vision</h5>
            <p class="card-text">Discover innovative solutions and pave the way for a brighter future.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">Creative Exploration</h5>
            <p class="card-text">Embark on a journey of creativity and let your imagination run wild.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <p>&copy; 2024 Creative Oasis. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
