{{-- resources/views/comments.blade.php --}}
<div class="comments-section">
    <h3>Comments ({{ $comments->count() }})</h3>

    <!-- Display the Comments -->
    @foreach($comments as $comment)
        <div class="comment">
            <strong>{{ $comment->user->name }}</strong> says:
            <p>{{ $comment->comment_text }}</p>
            <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <hr>
    @endforeach

    <!-- Comment Form -->
    @auth
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="commentable_type" value="{{ get_class($model) }}">
        <input type="hidden" name="commentable_id" value="{{ $model->id }}">
        <textarea name="comment_text" class="w-full border rounded" rows="4" placeholder="Add your comment"></textarea>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Post Comment</button>
    </form>
    @endauth
</div>