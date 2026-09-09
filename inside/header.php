<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta
      name="author"
      content="Mark Otto, Jacob Thornton, and Bootstrap contributors"
    />
    <meta name="generator" content="Hugo 0.84.0" />
    <title>Admmin Dashboard Template · Bootstrap v5.0</title>

    <link
      rel="canonical"
      href="https://getbootstrap.com/docs/5.0/examples/dashboard/"
    />

    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
     <style>
      /* Gallery images styling */
      .gallery-img {
        transition:
          transform 0.3s ease,
          box-shadow 0.3s ease;
        cursor: pointer;
      }

      .gallery-img:hover {
        transform: scale(1.1); /* zoom effect */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* shadow effect */
      }

      /* Modal image styling */
      #modalImage {
        width: 100%;
        height: 80vh; /* fixed height relative to viewport */
        object-fit: contain; /* ensures aspect ratio is preserved */
        display: block;
        margin: 0 auto;
      }

      #prevImage,
      #nextImage {
        font-size: 2rem;
        opacity: 0.7;
        transition: opacity 0.3s ease;
      }

      #prevImage:hover,
      #nextImage:hover {
        opacity: 1;
      }

      #likeBtn,
      #dislikeBtn {
        margin: 0 10px;
        transition: transform 0.2s ease;
      }

      #likeBtn:hover,
      #dislikeBtn:hover {
        transform: scale(1.1);
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="assets/dist/css/dashboard.css" rel="stylesheet" />
    <link href="assets/dist/css/features.css" rel="stylesheet" />
  </head>
  <body>
    <header
      class="navbar navbar-dark text-light py-3 sticky-top bg-primary flex-md-nowrap shadow"
    >
      <span class="col-md-3 col-lg-2 me-0 px-3"> Open Class Portal</span>

       <h6>Welcome, <?= $_SESSION['student_name'] ?>!</h6>

      <div class="d-flex gap-4 align-items-center me-0 px-3">
        <form method="POST" action="logout.php" class="d-inline">
          <button type="submit" class="btn btn-outline-warning btn-sm">Sign Out</button>
        </form>
      </div>
      <!-- <div></div> -->
    </header>

    <div class="container-fluid">
      <div class="row">
          <nav
          id="sidebarMenu"
          class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
        >
          <div class="position-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a
                  class="nav-link active"
                  data-bs-toggle="tab"
                  data-bs-target="#dashboard"
                  aria-current="page"
                >
                  <span data-feather="home"></span>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  data-bs-toggle="tab"
                  data-bs-target="#subjects"
                >
                  <span data-feather="file"></span>
                  Subjects
                </a>
              </li>

              <li class="nav-item">
                <a
                  class="nav-link"
                  data-bs-toggle="tab"
                  data-bs-target="#students"
                >
                  <span data-feather="users"></span>
                  Students
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  data-bs-toggle="tab"
                  data-bs-target="#resources"
                >
                  <span data-feather="shopping-cart"></span>

                  Resources</a
                >
              </li>
              
               <li class="nav-item">
                <a
                  class="nav-link"
                  data-bs-toggle="tab"
                  data-bs-target="#images"
                >
                  <span data-feather="image"></span>

                  Images</a
                >
              </li>

              <li class="nav-item">
                <a
                  class="nav-link"
                  data-bs-toggle="tab"
                  data-bs-target="#reactions"
                >
                  <span data-feather="file-text"></span>
                  Reactions</a
                >
              </li>
            </ul>
          </div>
        </nav>