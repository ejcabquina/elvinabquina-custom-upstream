<?php

/**
 * This block setups up a Plyr.io iframe. This framework was added to the theme in order to supply
 * an accessible video player. Documentation for this framework can be found at
 * https://github.com/sampotts/plyr
 *
 * This implmenenation uses the recommended `progressive enhancement` variation
 */

$video_id = get_field('video_id');
?>

<div class="plyr__video-embed plyr__youtube" style="position:relative; margin:0.5em 0;">
  <iframe
    src="https://www.youtube.com/embed/<?= $video_id ?>?origin=<?= get_site_url(); ?>&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
    allowfullscreen
    allowtransparency
    allow="autoplay"
	loading="lazy"
  ></iframe>
</div>
