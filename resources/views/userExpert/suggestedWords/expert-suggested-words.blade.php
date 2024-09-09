<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggested Words for Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Suggested Words Pending Approval</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Word</th>
                    <th>English Translation</th>
                    <th>Contributor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suggestedWords as $word)
                    <tr>
                        <td>{{ $word->text }}</td>
                        <td>{{ $word->english }}</td>
                        <td>{{ $word->user->name }}</td>
                        <td>{{ $word->status }}</td>
                        <td>
                            @if($word->status === 'pending')
                                <form action="{{ route('expert.words.approve', $word->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                            @else
                                <span class="text-success">Approved</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
