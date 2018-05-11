<?php

namespace EVEOnline\ESI\Mail;
use DateTime;

/**
 * Class MailBody
 *
 * @package EVEOnline\ESI\Mail
 */
class MailBody {

	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var \DateTime
	 */
	private $time;

	/**
	 * @var int
	 */
	private $from;

	/**
	 * @var string
	 */
	private $fromName;

	/**
	 * @var MailRecipient[]
	 */
	private $recipients;

	/**
	 * @var int[]
	 */
	private $labels;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * MailBody constructor.
	 *
	 * @param string $subject
	 * @param DateTime $time
	 * @param int $from
	 * @param MailRecipient[] $recipients
	 * @param int[] $labels
	 * @param string $body
	 */
	private function __construct(
		string $subject,
		DateTime $time,
		int $from,
		array $recipients,
		array $labels,
		string $body
	) {
		$this->subject = $subject;
		$this->time = $time;
		$this->from = $from;
		$this->fromName = "";
		$this->recipients = $recipients;
		$this->labels = $labels;
		$this->body = strip_tags($body, "<br><a>");
	}

	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * @return DateTime
	 */
	public function getTime(): DateTime {
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getFrom(): int {
		return $this->from;
	}

	/**
	 * @return string
	 */
	public function getFromName(): string {
		return $this->fromName;
	}

	/**
	 * @param string $fromName
	 */
	public function setFromName(string $fromName): void {
		$this->fromName = $fromName;
	}

	/**
	 * @return MailRecipient[]
	 */
	public function getRecipients(): array {
		return $this->recipients;
	}

	/**
	 * @return int[]
	 */
	public function getLabels(): array {
		return $this->labels;
	}

	/**
	 * @return string
	 */
	public function getBody(): string {
		return $this->body;
	}

	/**
	 * Creates MailBody from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return MailBody
	 */
	public static function create(array $json) {
		return new MailBody(
			$json['subject'],
			new DateTime($json['timestamp']),
			$json['from'],
			self::createMailRecipient($json),
			$json['labels'],
			$json['body']
		);
	}

	/**
	 * Creates an array of MailRecipient.
	 *
	 * @param array $json the global json
	 * @return MailRecipient[] the array of MailRecipient
	 */
	private static function createMailRecipient(array $json) {
		$recipients = array();
		// Retrieves channels if exists
		foreach ($json['recipients'] as $curRecipient) {
			$recipients[] = MailRecipient::create($curRecipient);
		}
		return $recipients;
	}

}
