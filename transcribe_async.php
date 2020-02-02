<?php 
	/**
	 * Copyright 2016 Google Inc.
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License");
	 * you may not use this file except in compliance with the License.
	 * You may obtain a copy of the License at
	 *
	 *     http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software
	 * distributed under the License is distributed on an "AS IS" BASIS,
	 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	 * See the License for the specific language governing permissions and
	 * limitations under the License.
	 */

	/**
	 * For instructions on how to run the full sample:
	 *
	 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/speech/README.md
	 */

	// Include Google Cloud dependendencies using Composer
	require_once __DIR__ . '/php-docs-samples/speech/vendor/autoload.php';
	
	// Google speech php objects
	require_once __DIR__ . '/google-cloud-php-speech/src/V1/RecognitionConfig.php';
	require_once __DIR__ . '/google-cloud-php-speech/src/V1/SpeechContext.php';
	require_once __DIR__ . '/google-cloud-php-speech/src/V1/SpeakerDiarizationConfig.php';
	require_once __DIR__ . '/google-cloud-php-speech/src/V1/SpeechContext.php';
	require_once __DIR__ . '/google-cloud-php-speech/src/V1/WordInfo.php';
		
	$_ = 'php-docs-samples/speech/src/transcribe_async.php';
	$gCloudURI = $_POST['storageUri'];

	use Google\Cloud\Speech\V1\SpeechClient;
	use Google\Cloud\Speech\V1\RecognitionAudio;
	use Google\Cloud\Speech\V1\RecognitionConfig;
	use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
	use Google\Cloud\Speech\V1\SpeakerDiarizationConfig;
	use Google\Cloud\Speech\V1\SpeechContext;
	use Google\Cloud\Speech\V1\WordInfo;

	// Configuration variables
	
	// File encoding method
	//$encoding = AudioEncoding::LINEAR16;
	$encoding = AudioEncoding::FLAC;
	
	// Sample rate of audio
	$sampleRateHertz = 16000;
	
	// Expected language spoken
	$languageCode = 'en-US';
	
	// When true, time offsets for every word will be included in the response.
	$enableWordTimeOffsets = true;
	
	// Set the model used for transcription
	$model  = 'phone_call';
	
	// Setting boost adaption to recognise specific words easier
	$keyWords = array(
		"alpha", "bravo", "charlie", 
		"delta", "echo", "foxtrot", 
		"golf", "hotel", "india", 
		"juliet", "kilo", "lima", 
		"mike", "november", "oscar", 
		"papa", "quebec", "romeo", 
		"sierra", "tango", "uniform", 
		"victor", "whisky", "x-ray", 
		"yankee", "zulu", "zero",
		"one", "two", "three",
		"four", "five", "six",
		"seven", "eight", "nine");
	
	$speechContext = new SpeechContext();
	$speechContext->setPhrases = $keyWords;
	
	// Speaker Diarization Config (Speaker recognition
	$diarConfig = new SpeakerDiarizationConfig();
	$diarConfig->setEnableSpeakerDiarization(true);

	// set string as audio content
	$audio = (new RecognitionAudio())
		//->setContent($content);
		->setUri($gCloudURI);
	
	// Setup the config with all of the variables set above
	$recognitionConfig = new RecognitionConfig();
	$recognitionConfig->setEncoding($encoding);
	$recognitionConfig->setSampleRateHertz($sampleRateHertz);
	$recognitionConfig->setLanguageCode($languageCode);
	$recognitionConfig->setUseEnhanced(true);
	$recognitionConfig->setModel($model);
	$recognitionConfig->setEnableWordTimeOffsets($enableWordTimeOffsets);
	$recognitionConfig->setDiarizationConfig($diarConfig);
	$recognitionConfig->setSpeechContexts(array($speechContext));

	// create the speech client
	$client = new SpeechClient();
	
	
	// create the asyncronous recognize operation
	$operation = $client->longRunningRecognize($recognitionConfig, $audio);
	$operation->pollUntilComplete();

	if ($operation->operationSucceeded()) {
		$response = $operation->getResult();

		// each result is for a consecutive portion of the audio. iterate
		// through them to get the transcripts for the entire audio file.
		foreach ($response->getResults() as $result) {
			$alternatives = $result->getAlternatives();
			$mostLikely = $alternatives[0];
			$transcript = $mostLikely->getTranscript();
			$confidence = $mostLikely->getConfidence();
			echo '<b>Transcript</b>: '.$transcript.'<br>';
			echo '<b>Confidence</b>: '.$confidence.'<br>';
			printf('Transcript: %s' . PHP_EOL, $transcript);
			printf('Confidence: %s' . PHP_EOL, $confidence);
			foreach ($mostLikely->getWords() as $wordInfo) {
				$startTime = $wordInfo->getStartTime();
				$endTime = $wordInfo->getEndTime();
				printf('  Speaker: %s | Word: %s (start: %s, end: %s)<br>' . PHP_EOL,
					$wordInfo->getSpeakerTag(),
					$wordInfo->getWord(),
					$startTime->serializeToJsonString(),
					$endTime->serializeToJsonString());
			}
		}
	} else {
		print_r($operation->getError());
		
	}
	$client->close();

	# [END speech_transcribe_sync]
	
?>