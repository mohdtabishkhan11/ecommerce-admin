<!DOCTYPE html>
<html>

<head>
    <title>Add New User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    @include('layouts.navbar')
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3>Add New User</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <!-- Profile Image -->
                    <div class="mb-3">
                        <label>Upload Profile Image:</label>
                        <input type="file" name="profile_image" class="form-control">
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

                    <button type="submit" class="btn btn-success">Create</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>