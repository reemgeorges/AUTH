<html>

<head>
    <title>All Posts</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="m-3 text-center">All Posts</h1>
        <div class="text-end mb-3">

            @if (in_array('writer', Auth::user()->roles->pluck('role_name')->toArray()) || in_array('admin', Auth::user()->roles->pluck('role_name')->toArray()))
                <a href="{{ url('/posts/create') }}" class="btn btn-success" title="Add New post">
                    Add New
                </a>
            @endif
        </div>

        <div class="text-end mb-3">

            @if (in_array('admin', Auth::user()->roles->pluck('role_name')->toArray()))
                <a href="{{ route('blacklistedWords.index')}}" class="btn btn-danger" title="View Blacklisted Words">
                    View Blacklisted Words
                </a>
            @endif
        </div>

        <table class="table table-bordered mb-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Content</th>
                    <th>Published Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->desc }}</td>
                        <td>{{ $post->content }}</td>
                        <td>{{ $post->published_date }}</td>
                        <td>
                            @if (request()->has('trashed'))
                                <a href="{{ route('posts.restore', $post->id) }}" class="btn btn-success">Restore</a>
                            @else
                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Delete">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="float-end">
            @if (request()->has('trashed'))
                <a href="{{ route('posts.index') }}" class="btn btn-info">View All posts</a>
            @else
                <a href="{{ route('posts.index', ['trashed' => 'post']) }}" class="btn btn-primary">View Deleted
                    posts</a>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').click(function(e) {
                if (!confirm('Are you sure you want to delete this post?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
