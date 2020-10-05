<div class="col-md-4">
    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">
                Berichten
            </h3>
        </div>

        @can('add-asset-comment')
            <div class="box-body">
                <form action="{{route('asset.addComment', ['id' => $asset->id])}}" id="form" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <label for="message">Bericht</label>
                    <textarea name="message" class="form-control" style="max-width: 100%; margin-bottom: 10px"
                              id="message"></textarea>
                    <div class="form-group" class="pull-left">
                        <label for="imageFile">Upload foto</label>
                        <input id="messageFile" type='file' name="imageFile"></div>
                    <input type="submit" class="btn btn-success pull-right" value="Plaats bericht">
                </form>
            </div>
        @endcan

        <div class="box-footer comments">
            @foreach($comments as $comment)
                <div class="direct-chat-msg">
                    <a href="/users/{{auth()->user()->id}}">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">{{auth()->user()->name}}</span>
                            <span class="direct-chat-timestamp">{{$comment->created_at}}</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="/uploads/avatars/default.png"
                             alt="user image"><!-- /.direct-chat-img -->
                    </a>
                    <div class="direct-chat-text bg-green">
                        <p>{{$comment->message}}</p>
                        @foreach($commentPic as $image)
                            @if($image->comment_id == $comment->id)
                                <a href="/uploads/comments/{{$image->image_url}}" data-lightbox="image-1"
                                   data-title="{{$comment->message}}">
                                    <img class="comment-image" src="/uploads/comments/{{$image->image_url}}"
                                         alt="commentpicture">
                                </a>
                                <a class="btn-sm btn-block btn btn-primary" download="{{$image->image_url}}"
                                   href="/uploads/comments/{{$image->image_url}}" title="downloadimage">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Download foto
                                </a>
                            @endif
                        @endforeach
                    </div>
                    <!-- /.direct-chat-text -->
                </div>
            @endforeach
        </div>
    </div>
</div>