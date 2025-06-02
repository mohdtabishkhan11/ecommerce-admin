<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 60px auto;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #2575fc;
            text-decoration: none;
        }

        .login-link a:hover {
            /* text-decoration: underline; */
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        <div class="register-container mt-5">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <h2 class="text-center text-primary mb-4">Register</h2>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <!-- Profile Image -->
                <div class="mb-3">
                    <input type="file" class="form-control" name="profile_image" accept="image/*">
                    @error('profile_image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>  
                <!-- Gender -->
                <div class="mb-3">
                    <label>Gender:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="male">
                        <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="female">
                        <label class="form-check-label">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="other">
                        <label class="form-check-label">Other</label>
                    </div>
                </div>

                <!-- Designation -->
                <div class="mb-3">
                    <label>Designation:</label>
                    <select class="form-select" name="designation">
                        <option value="">Select Designation</option>
                        <option value="Web Developer">Web Developer</option>
                        <option value="Designer">Designer</option>
                        <option value="Social Media Manager">Social Media Manager</option>
                    </select>
                </div>

                <!-- Skills -->
                <div class="mb-3">
                    <label>Skills:</label><br>
                    <input type="checkbox" name="skills[]" value="HTML"> HTML
                    <input type="checkbox" name="skills[]" value="CSS"> CSS
                    <input type="checkbox" name="skills[]" value="JavaScript"> JavaScript
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <div class="login-link">
                <p> <a href="{{ route('login') }}"> Already have an account? Login</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>