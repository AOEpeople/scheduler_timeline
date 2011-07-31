/**
 *
 */
$(function() {
	$('.task').click(function() {
		var $this = $(this);
		$('.task').removeClass('active');
		$this.addClass('active');
		$('#details').html($this.find('.details').html());
	})

	// collision detection

	function getPositions(box) {
		var $box = $(box);
		var pos = $box.position();
		var width = $box.width();
		var height = $box.height();
		return [ [ pos.left, pos.left + width ], [ pos.top, pos.top + height ] ];
	}

	function comparePositions(p1, p2) {
		var x1 = p1[0] < p2[0] ? p1 : p2;
		var x2 = p1[0] < p2[0] ? p2 : p1;
		return x1[1] > x2[0] || x1[0] === x2[0] ? true : false;
	}

	function collision(a, b) {
		var posA = getPositions(a);
		var posB = getPositions(b);

		return (posA[1][0] == posB[1][0]) && comparePositions(posA[0], posB[0]);
	}

//	console.log(getPositions($('#uid_1281'))[1][0]);
//	console.log(getPositions($('#uid_1282')));

	$('.timeline').each(function() {
		var $timeline = $(this);
		var $tasks = $('.task', $timeline);
		var numberOfTasks = $tasks.length;
		for (var i=0; i<numberOfTasks; i++) {
			var u = Math.min(i+5, numberOfTasks);
			for (var j = i+1; j < u; j++) {
				if (collision($tasks[i], $tasks[j])) {
					var $subject = $($tasks[i]);
					var $object = $($tasks[j]);

					var objectTop = parseInt($subject.css('top'));

					// $subject.css('top', (objectTop-4) + 'px');
					$object.css('top', (objectTop+4) + 'px');

					$subject.css('height', 18);
					$object.css('height', 18);

				}
			}
		}
	});


});