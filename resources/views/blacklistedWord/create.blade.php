<html>
<head>
    <title>Add Blacklisted Word</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="m-3 text-center">Add Blacklisted Word</h1>
        <form action="{{ route('blacklistedWords.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="word" class="form-label">Word</label>
                <input type="text" class="form-control" id="word" name="word" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Word</button>
        </form>
    </div>
</body>
</html>
