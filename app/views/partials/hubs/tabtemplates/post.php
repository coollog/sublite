<tabtemplate for="post">
  <table class="post" index="{id}"><tr>
    <td class="l"><pic style="background-image: url('{pic}');"></pic></td>
    <td class="r">
      {text}
      <info>By {name} from {hub}, {time}</info>
      <likes>{likes}</likes><replies>{replies}</replies>
    </td>
  </tr></table>
  <div class="thread" for="{id}">
    <div class="replies"></div>
    <div class="reply">
      Write your comment:
      <form>
        <textarea name="text"></textarea>
        <right><button>Reply</button></right>
      </form>
    </div>
  </div>
</tabtemplate>