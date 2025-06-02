<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.01);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }

        .btn-custom {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>



    <div class="container">
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary">All Users</h2>
        </div>  
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-success">Products List</a>
            <a href="{{ route('users.create') }}" class="btn btn-success">+ Add New User</a>
        </div>  
        </div>
       

        <div class="row">
            @foreach($users as $index => $user)
                <div class="col-md-4 mb-4">
                    <div class="card p-3">
                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('uploads/placeholder.png') }}"
                            class="profile-img mb-3" alt="Profile">

                        <h5 class="text-center">{{ $user->name }}</h5>
                        <p class="text-center text-muted mb-1">{{ $user->email }}</p>

                        <ul class="list-group list-group-flush mb-2">
                            <li class="list-group-item"><strong>Gender:</strong> {{ ucfirst($user->gender) }}</li>
                            <li class="list-group-item"><strong>Designation:</strong> {{ $user->designation }}</li>
                            <li class="list-group-item">
                                <strong>Skills:</strong>
                                @if($user->skills)
                                    {{ implode(', ', json_decode($user->skills)) }}
                                @else
                                    <em>None</em>
                                @endif
                            </li>

                        </ul>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>