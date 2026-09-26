<?php
include '../includes/header.php';
include '../includes/navbar.php';
?>

<style>
    .gallery-section {
        padding: 60px 0;
        min-height: 70vh;
    }

    .gallery-heading {
        text-align: center;
        margin-bottom: 15px;
        font-weight: 700;
        color: #f9f6f6;
    }

    .gallery-description {
        text-align: center;
        color: #666;
        max-width: 700px;
        margin: 0 auto 40px;
    }

    .gallery-card {
        height: 100%;
        overflow: hidden;
        border: none;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.10);
        transition: transform 0.3s ease,
                    box-shadow 0.3s ease;
    }

    .gallery-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.16);
    }

    .gallery-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
    }

    .gallery-card .card-body {
        padding: 20px;
    }

    .gallery-card .card-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .gallery-card .card-text {
        color: #666;
        font-size: 14px;
        margin-bottom: 0;
    }

    @media (max-width: 576px) {
        .gallery-section {
            padding: 35px 0;
        }

        .gallery-image {
            height: 220px;
        }
    }
</style>

<section class="gallery-section">
    <div class="container">

        <h1 class="gallery-heading">Our Event Gallery</h1>

        <p class="gallery-description">
            Explore moments from different types of events.
            Browse our gallery to discover celebrations,
            fashion shows, music, and special wedding occasions.
        </p>

        <div class="row g-4">

            <!-- Photo 1: Djnight -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card gallery-card">
                    <img
                        src="../assets/images/Djnight.webp"
                        class="gallery-image"
                        alt="D Night Event"
                    >

                    <div class="card-body">
                        <h5 class="card-title">DJ Night</h5>
                        <p class="card-text">
                            A memorable evening filled with music,
                            entertainment, and celebration.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Photo 2: Fashion Show -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card gallery-card">
                    <img
                        src="../assets/images/Fashionshow.jpg"
                        class="gallery-image"
                        alt="Fashion Show"
                    >

                    <div class="card-body">
                        <h5 class="card-title">Fashion Show</h5>
                        <p class="card-text">
                            A showcase of fashion, creativity,
                            style, and confidence.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Photo 3: Sangeet Ceremony -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card gallery-card">
                    <img
                        src="../assets/images/SangeetCeremony.jpeg"
                        class="gallery-image"
                        alt="Sangeet Ceremony"
                    >

                    <div class="card-body">
                        <h5 class="card-title">Sangeet Ceremony</h5>
                        <p class="card-text">
                            A joyful celebration of music, dance,
                            and family traditions.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Photo 4: Wedding Function -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card gallery-card">
                    <img
                        src="../assets/images/WeddingFunction.jpeg"
                        class="gallery-image"
                        alt="Wedding Function"
                    >

                    <div class="card-body">
                        <h5 class="card-title">Wedding Function</h5>
                        <p class="card-text">
                            Beautiful wedding moments filled with
                            love, happiness, and celebration.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
include '../includes/footer.php';
?>