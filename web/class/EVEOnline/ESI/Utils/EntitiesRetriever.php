<?php

namespace EVEOnline\ESI\Utils;

use EVEOnline\ESI\EsiFactory;

/**
 * Helps to retrieve entities's name from their ID.
 *
 * @package EVEOnline\ESI\Utils
 */
class EntitiesRetriever {

	/** @var array */
	private static $ENTITY_INFO = array();

	/**
	 * Retrieves entity information and cache them for next sessions.
	 *
	 * @param SimpleEntityInfo[] $entities to complete with its ID as the key of the array
	 * @return SimpleEntityInfo[] the SimpleEntityInfo array or empty
	 */
	public static function getEntityInfo(
		array &$entities
	): array {
		if (!is_array($entities) || empty($entities)) {
			return array();
		}

		$idsToFetch = array();
		foreach ($entities as $entityId => $entity) {
			// Prepare the cache if not existing yet
			if (!array_key_exists($entityId, self::$ENTITY_INFO)) {
				self::$ENTITY_INFO[$entityId] = $entity;
				$idsToFetch[] = intval($entityId);
			}
		}

		if (!empty($idsToFetch)) {
			// Sets the parameters
			$res = EsiFactory::invoke(
				null,
				"post",
				"/universe/names/",
				array(),
				array(),
				$idsToFetch
			);
			$json = json_decode($res->raw, true);
			foreach ($json as $entity) {
				$entity = SimpleEntityInfo::create($entity);
				self::$ENTITY_INFO[$entity->getId()]->setName($entity->getName());

				// Update back the array which was given (save time)
				$entities[$entity->getId()]->setName(self::$ENTITY_INFO[$entity->getId()]->getName());
			}
		}
		return $entities;
	}

}
