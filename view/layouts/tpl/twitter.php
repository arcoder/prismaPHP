<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 6,
  interval: 30000,
  width: 'auto',
  height: 300,
  theme: {
    shell: {
      background: '#99cccc',
      color: '#000333'
    },
    tweets: {
      background: '#99cccc',
      color: '#000333',
      links: '#333333'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'default'
  }
}).render().setUser('dashy_fw').start();
</script>