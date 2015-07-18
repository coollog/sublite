<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  commentsview {
    width: 500px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    display: block;
    overflow-y: scroll;
    height: 500px;
  }
  comments, replies {
    position: absolute;
    background: #fee;
    width: 100%;
    display: block;
    transition: 0.2s all ease-in-out;
  }
  comment {
    background: #ffe;
    padding: 10px 50px;
    display: block;
  }
  replies {
    display: none;
  }
</style>

<commentsview>
  <comments>
    <comment>
      Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
      <replies>
        <comment>
          hahahahah this is soooooo funnnnyyyyy
        </comment>
        <comment>
          hahahahah this is soooooo not    funnnnyyyyy
        </comment>
        <comment>
          oooooo  hhhahhahaaaaaahahahahhaahhaahhaah
          <replies>
            <comment>
              ur an idiot
            </comment>
            <comment>
              whatever
            </comment>
          </replies>
        </comment>
      </replies>
    </comment>
  </comments>
</commentsview>

<script>
  var offset = 0;
  $('comment').click(function (e) {
    e.stopPropagation();
    var curOffset = -$(this).offset().left;
    offset = curOffset+offset;
    $('comments').css('left', (offset) + 'px');
    $(this).children('replies').slideToggle(200);
  });
</script>