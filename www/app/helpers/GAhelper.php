<?php 

/**
 * Generate google analytics code
 *
 * @return  void
 */
function ga_code()
{
  // Only production pages need to be tracked.
    if (App::environment() != 'production')
    {
        return;
    }
/*
    echo <<<EOD
<!-- google analytics traker-->
<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-32405849-5', 'wexchange.com');
ga('send', 'pageview');
</script>
<!-- //google analytics traker-->
EOD;
*/
}