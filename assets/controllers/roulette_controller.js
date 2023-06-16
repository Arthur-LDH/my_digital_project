import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        restaurantNames: Array
    }

    connect() {
        var rouletteSize = 380;
        var restaurantNames = this.restaurantNamesValue;
        restaurantNames = checkArrayLength(restaurantNames);
        var numberSlots = restaurantNames.length;
        var slotAngle = 360 / numberSlots;
        var degrees = (180 - slotAngle) / 2;
        var slotHeight = Math.tan(degrees * Math.PI / 180) * (rouletteSize / 2);

        $(".roulette").css({
            'width': rouletteSize + 'px',
            'height': rouletteSize + 'px',
        });

        $('head').append('<style id="afterNumber"></style>');

        let i = -2;
        restaurantNames.forEach(name => {
            $(".roulette").append('<div class="option_roulette option_roulette-' + i + '"></div>');
            var classS = '.option_roulette-' + i;

            if (i != 0) {
                $(classS).css({
                    'transform': 'rotate(' + slotAngle * i + 'deg)',
                    'border-bottom-color': getRandomColor(i)
                });
            } else {
                $(classS).css({
                    'border-bottom-color': getRandomColor(i)
                });
            }


            $('#afterNumber').append('.option_roulette-' + i + '::before {content: "' + name + '"}');

            $(".option_roulette")
                .attr('data-content', i)
                .attr('data-width', (rouletteSize / 2) + 'px')
                .attr('data-line', (rouletteSize / 2) + 'px');

            i++
        });

        $(".option_roulette").css({
            'border-bottom-width': slotHeight + 'px',
            'border-right-width': (rouletteSize / 2) + 'px',
            'border-left-width': (rouletteSize / 2) + 'px'
        });

        function checkArrayLength(restaurantNames){
            if(restaurantNames.length <= 5){
              restaurantNames.forEach(name => {
                if(restaurantNames.length == 7){
                  return restaurantNames;
                }
                restaurantNames.push(name);
              })
      
              return checkArrayLength(restaurantNames);
            }
            return restaurantNames;
          }

        function getRandomColor(i) {
            var colors = ["#2C9D8F", "#F5A261", "#E76F52", "#2C9D8F", "#F5A261", "#E76F52", "#2C9D8F" ]
            let color = colors[i + 2]
            return color;
        }

        function turnRoulette() {
            var num;
            var numID = 'number-';
            num = -2;
            numID += num;

            $('#animationRoulette').remove();
            $('head').append('<style id="animationRoulette">' +
                '#number-' + num + ' { animation-name: number-' + num + '; } ' +
                '@keyframes number-' + num + ' {' +
                'from { transform: rotate(0); } ' +
                'to { transform: rotate(' + (360 * (numberSlots - 1) - slotAngle * num) + 'deg); }' +
                '}' +
                '</style>'
            );

            $('.roulette').removeAttr('id').attr('id', numID);

            setTimeout(zoomRoulette, 7000);
        }

        $('.roulette').before().click(function () {
            turnRoulette();
        });

        $('.start').click(function () {
            turnRoulette();
        });


        function zoomRoulette() {
            $('.roulette-section').addClass('zoom-section');
            setTimeout(destroyRoulette, 1500);
        }

        function destroyRoulette() {
            $('.roulette-section').remove();
        }
    }
}