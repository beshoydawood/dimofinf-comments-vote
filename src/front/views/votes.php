<div class="dvc-votes">
    <?php if( 'dislikes' !== $view_settings  ): ?>
    <a href="#" class="dvc-like">
        <svg x="0px" y="0px" viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;">
	        <path d="M9.5,43c-2.757,0-5,2.243-5,5s2.243,5,5,5s5-2.243,5-5S12.257,43,9.5,43z"/>
            <path d="M56.5,35c0-2.495-1.375-3.662-2.715-4.233C54.835,29.85,55.5,28.501,55.5,27c0-2.757-2.243-5-5-5H36.134l0.729-3.41
		    c0.973-4.549,0.334-9.716,0.116-11.191C36.461,3.906,33.372,0,30.013,0h-0.239C28.178,0,25.5,0.909,25.5,7c0,14.821-6.687,15-7,15
		    h0h-1v-2h-16v38h16v-2h28c2.757,0,5-2.243,5-5c0-1.164-0.4-2.236-1.069-3.087C51.745,47.476,53.5,45.439,53.5,43
		    c0-1.164-0.4-2.236-1.069-3.087C54.745,39.476,56.5,37.439,56.5,35z M3.5,56V22h12v34H3.5z"/></svg>
    </a>
    <?php endif; ?>
    <?php if( 'likes' !== $view_settings  ): ?>
    <a href="#" class="dvc-dislike">
        <svg x="0px" y="0px" viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" >
            <path d="M40.5,0v2h-28c-2.757,0-5,2.243-5,5c0,1.164,0.4,2.236,1.069,3.087C6.255,10.524,4.5,12.561,4.5,15
                c0,1.164,0.4,2.236,1.069,3.087C3.255,18.524,1.5,20.561,1.5,23c0,2.495,1.375,3.662,2.715,4.233C3.165,28.15,2.5,29.499,2.5,31
                c0,2.757,2.243,5,5,5h14.366l-0.729,3.41c-0.973,4.551-0.334,9.717-0.116,11.191C21.539,54.094,24.628,58,27.987,58h0.239
                c1.596,0,4.274-0.909,4.274-7c0-14.82,6.686-15,7-15h0h1v2h16V0H40.5z M54.5,36h-12V2h12V36z"/>
            <path d="M48.5,15c2.757,0,5-2.243,5-5s-2.243-5-5-5s-5,2.243-5,5S45.743,15,48.5,15z"/>
        </svg>
    </a>
    <?php endif; ?>
</div>