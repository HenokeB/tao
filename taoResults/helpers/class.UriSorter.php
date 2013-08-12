<?php
class taoResults_helpers_UriSorter {
	
	public static function sort($uris) {
		sort($uris);
		
		$byTs = array();
		foreach ($uris as $uri) {
			list($namespace, $identifier) = explode('#', $uri);
			$ts = substr($identifier, 1, 10);
			if (!isset($byTs[$ts])) {
				$byTs[$ts] = array();
			}
			$byTs[$ts][] = $uri;
		}
		$correct = array();
		foreach ($byTs as $ts => $uris) {
			if (count($uris) == 1) {
				$correct[] = current($uris);
			} else {
				$corrected = self::advancedSort($uris);
				
				foreach ($corrected as $uri) {
					$correct[] = $uri;
				}
			}
		}
		return $correct;
	}
	
	private static function advancedSort($uris) {
		$others = $uris;
		$first = array_pop($others); 
		common_Logger::i('Advanced Sort on '.count($uris).' elements, reference: '.$first);
		$best = null;
		for ($outer = 1; $outer <= 4; $outer++) {
			$possibleValue = substr($first, strpos($first, '#')+12+$outer);
			$values = self::getClosestMatches($possibleValue, $others);
			$avg = array_sum($values) / count($values);
			$distance = 0;
			foreach ($values as $val) {
				$distance += abs($val - $avg);
			}
			if (is_null($best) || $distance < $refDistance) {
				$best = $values;
				$refDistance = $distance;
			}
		}
		sort($best);
		common_Logger::d('ids identified: '.implode(',', $best));
		$result = array();
		foreach ($best as $end) {
			$found = false;
			foreach ($uris as $uri) {
				if (substr($uri, -strlen($end)) == $end) {
					$result[] = $uri;
					$found = true;
					break;
				}
			}
			if (!$found) {
				throw new exception('could not find uri for '.$end);
			}
		}
		
		return $result;
	}
	
	private static function getClosestMatches($reference, $uris) {
		$values = array($reference);
		foreach ($uris as $uri) {
			$best = null; 
			for ($i = 1; $i <= 4; $i++) {
				$val = substr($uri, strpos($uri, '#')+12+$i);
				if (is_null($best) || $distance > abs($reference - $val)) {
					$best = $val;
					$distance = abs($reference - $val);
				}
			}
			$values[] = $best;
		}
		return $values;
	}
}