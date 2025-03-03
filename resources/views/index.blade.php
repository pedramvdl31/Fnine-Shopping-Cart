@extends('layouts.main')

@section('styles')


@endsection

@section('scripts')
    

@endsection

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* SCSS Styles */
        :root {
            --primary-color: #007bff;
            --light-gray: #f8f9fa;
        }
        
        body {
            background-color: var(--light-gray);
            font-family: Arial, sans-serif;
        }
        
        .navbar {
            background-color: var(--primary-color) !important;
        }
        
        .cart-icon {
            font-size: 1.5rem;
            color: white;
            position: relative;
        }
        
        .navbar .cart-container {
            margin-left: auto;
            position: relative;
        }
        
        .cart-badge {
          position: absolute;
          top: -15px;
          right: -8px;
          background: red;
          color: white;
          font-size: 10px;
          border-radius: 50%;
          padding: 2px 6px;
        }
        
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: scale(1.05);
        }
        
        .product-img {
            height: 200px;
            object-fit: cover;
        }
        
        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .price {
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .add-to-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-3 d-flex">
        <a class="navbar-brand text-white" href="#">E-Commerce</a>
        <div class="cart-container">
            <a href="/cart" class="position-relative">
                <i class="bi bi-cart cart-icon"></i>
                <span class="cart-badge">3</span> <!-- Replace 3 with dynamic cart count -->
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4">
                    <div class="card product-card">
                        <img src="{{ asset($product->image_url) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <p class="price">${{ number_format($product->price, 2) }}</p>
                            <p class="stock">Stock: {{ $product->stock }}</p>
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <label for="quantity-{{ $product->id }}" class="me-2">Qty:</label>
                                <input type="number" id="quantity-{{ $product->id }}" class="form-control w-25" value="1" min="1" max="{{ $product->stock }}">
                            </div>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection