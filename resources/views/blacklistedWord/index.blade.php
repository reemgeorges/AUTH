<html>
<head>
    <title>Blacklisted Words</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="m-3 text-center">Blacklisted Words</h1>
        <div class="mb-3">
            <a href="{{ route('blacklistedWords.create') }}" class="btn btn-success">Add Word</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Word</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blacklistedWords as $word)
                    <tr>
                        <td>{{ $word->word }}</td>
                        <td>
                            <form action="{{ route('blacklistedWords.destroy', $word->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
