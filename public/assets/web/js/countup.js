function debounce(func, wait, immediate) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      const later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }
  
  function isInViewport(element) {
    if (!element || typeof element.getBoundingClientRect !== 'function') {
      return false;
    }
  
    const rect = element.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }
  
  const startCounterAnimation = function() {
    $('.timer').each(function() {
      const $this = $(this);
      const options = $.extend({}, $this.data('countToOptions'));
      $this.countTo(options);
    });
  };
  
  const scrollHandler = debounce(function() {
    const section = $('.counter');
    if (isInViewport(section[0])) {
      startCounterAnimation();
    }
  }, 200);
  
  $(document).ready(function() {
    if (isInViewport($('.counter')[0])) {
      startCounterAnimation();
    }
    $(window).on('scroll', scrollHandler);
  });
  
  (function ($) {
    $.fn.countTo = function (options) {
      options = options || {};
      return this.each(function () {
        const settings = $.extend({}, $.fn.countTo.defaults, {
          from:            $(this).data('from'),
          to:              $(this).data('to'),
          speed:           $(this).data('speed'),
          refreshInterval: $(this).data('refresh-interval'),
          decimals:        $(this).data('decimals')
        }, options);
    
        let loops = Math.ceil(settings.speed / settings.refreshInterval);
        const increment = (settings.to - settings.from) / loops;
        const $self = $(this);
        let loopCount = 0;
        let value = settings.from;
        const data = $self.data('countTo') || {};
    
        $self.data('countTo', data);
        if (data.interval) {
          clearInterval(data.interval);
        }
        data.interval = setInterval(updateTimer, settings.refreshInterval);
        render(value);
    
        function updateTimer() {
          value += increment;
          loopCount++;
          render(value);
    
          if (typeof(settings.onUpdate) === 'function') {
            settings.onUpdate.call(this, value);
          }
    
          if (loopCount >= loops) {
            $self.removeData('countTo');
            clearInterval(data.interval);
            value = settings.to;
    
            if (typeof(settings.onComplete) === 'function') {
              settings.onComplete.call(this, value);
            }
          }
        }
    
        function render(value) {
          const formattedValue =  settings.formatter.call(this, value, settings);
          $self.html(formattedValue  + "+");
        }
      });
    };
    
    $.fn.countTo.defaults = {
      from: 0,
      to: 0,
      speed: 1000,
      refreshInterval: 100,
      decimals: 0,
      formatter: function(value, settings) {
        return value.toFixed(settings.decimals);
      },
      onUpdate: null,
      onComplete: null
    };
  }(jQuery));