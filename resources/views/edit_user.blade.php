<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f0f2f5;
        }

        .edit-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .form-section {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        <div class="edit-container">
            <h2 class="text-center text-primary">Edit User</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image:</label>
                    <input type="file" name="profile_image" class="form-control">
                    @if($user->profile_image)
                        <img src="{{ asset('uploads/' . $user->profile_image) }}" alt="Profile" class="profile-img mt-2">
                    @else
                        <img src="{{ asset('uploads/placeholder.png') }}" alt="Placeholder" class="profile-img mt-2">
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender:</label><br>
                    @php $genders = ['male', 'female', 'other']; @endphp
                    @foreach($genders as $gender)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="{{ $gender }}" {{ $user->gender === $gender ? 'checked' : '' }}>
                            <label class="form-check-label">{{ ucfirst($gender) }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label class="form-label">Designation:</label>
                    <select name="designation" class="form-select" required>
                        <option value="">Select Designation</option>
                        @foreach(['Web Developer', 'Designer', 'Social Media Manager'] as $designation)
                            <option value="{{ $designation }}" {{ $user->designation === $designation ? 'selected' : '' }}>
                                {{ $designation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills:</label><br>
                    @php
                        $skills = ['HTML', 'CSS', 'JavaScript', 'Laravel', 'Photoshop'];
                        $userSkills = $user->skills ? explode(',', $user->skills) : [];
                    @endphp
                    @foreach($skills as $skill)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="{{ $skill }}" {{ in_array($skill, $userSkills) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $skill }}</label>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary w-100">Update User</button>
            </form>
        </div>
    </div>

</body>

</html>