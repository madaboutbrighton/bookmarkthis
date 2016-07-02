[[+nojs:notempty=`
<a href="[[+share]]" [[+custom]] title="Share this page on [[+name]]" rel="nofollow" ><img src="[[+image]]" alt="[[+name]]" [[+nojs:default=`onclick="return BookmarkThis.share('[[+share]]', this);"`]]/>
  <img [[+custom]] title="Share this page on [[+name]]" src="[[+image]]" alt="[[+name]]" />
</a>
`]]
[[+nojs:default=`
<img [[+custom]] title="Share this page on [[+name]]" src="[[+image]]" alt="[[+name]]" onclick="return BookmarkThis.share('[[+share]]', this);" />
`]]