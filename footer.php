<!-- footer.php -->
<style>
  .contact {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    background-color: #222;
    color: white;
    padding: 50px 20px;
    font-family: 'Open Sans', sans-serif;
  }

  .main-contact {
    flex: 1 1 200px;
    margin: 15px;
    min-width: 200px;
  }

  .main-contact h3 {
    font-size: 20px;
    margin-bottom: 10px;
    text-transform: uppercase;
  }

  .main-contact h5 {
    font-size: 14px;
    margin-bottom: 15px;
    font-weight: normal;
  }

  .main-contact li {
    list-style: none;
    margin-bottom: 8px;
  }

  .main-contact a {
    text-decoration: none;
    color: white;
    font-size: 14px;
    transition: color 0.3s ease;
  }

  .main-contact a:hover {
    color: #aaa;
    text-decoration: underline;
  }

  .icons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
  }

  .icons a {
    font-size: 24px;
    color: white;
    transition: color 0.3s ease;
  }

  .icons a:hover {
    color: #aaa;
  }

  @media (max-width: 768px) {
    .contact {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
  }
</style>

<section class="contact" id="contact">
  <div class="main-contact">
    <h3>Classix</h3>
    <h5>Let's Connect With Us</h5>
    <div class="icons">
      <a href="#"><i class='bx bxl-facebook-square'></i></a>
      <a href="#"><i class='bx bxl-instagram-alt'></i></a>
      <a href="#"><i class='bx bxl-twitter'></i></a>
    </div>
  </div>

  <div class="main-contact">
    <h3>Explore</h3>
    <li><a href="home.php">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="sale.php">Sale</a></li>
    <li><a href="contact.php">Contact Us</a></li>
  </div>

  <div class="main-contact">
    <h3>Our Service</h3>
    <li><a href="#">Pricing</a></li>
    <li><a href="#">Free Shipping</a></li>
    <li><a href="#">Gift Cards</a></li>
  </div>

  <div class="main-contact">
    <h3>Shopping</h3>
    <li><a href="#">Clothing Store</a></li>
    <li><a href="#">Trending Shoes</a></li>
    <li><a href="#">Accessories</a></li>
    <li><a href="#">Sale</a></li>
  </div>
</section>