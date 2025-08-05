<!-- This is an insert so no layout required -->
<div class="comments-section">
    <h3 class="font-bold text-lg mb-4">Comments ({{ $comments->count() }})</h3>
    <!-- Display the Comments -->
    @foreach($comments as $comment)
        <div class="comment my-6 border border-zinc-300 shadow-lg rounded-md p-4 text-sm">
            <strong><a href="/profile/{{ $comment->user->name_slug }}">{{ $comment->user->name }}</a></strong> said <span class="">{{ $comment->created_at->diffForHumans() }}</span>
            <p class="mt-4">{{ $comment->comment_text }}</p>
        </div>
    @endforeach
    <!-- Comment Form -->
    @can('Member')
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($model) }}">
            <input type="hidden" name="commentable_id" value="{{ $model->id }}">
            <textarea name="comment_text" class="w-full border border-zinc-300 shadow-lg rounded p-2" rows="2" placeholder="Add your comment"></textarea>
            <button type="submit" class="my-4 border border-zinc-300 rounded-md p-2 bg-lime-300 hover:bg-lime-500 cursor-pointer">Submit Comment</button>
        </form>
    @endcan
    @guest
        <div class="mt-4 font-bold">
            <p class="dark:text-white">Want to comment on this page? <a href="/login" class="text-lime-700">Login</a> or <a href="/register" class="text-lime-700">Register</a>.</p>
        </div>
    @endguest
</div>