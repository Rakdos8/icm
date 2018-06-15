<?php

namespace EVEOnline\ESI\Utils;
use EVEOnline\ESI\EsiFactory;
use EVEOnline\ESI\Utils\Enums\EntityType;

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

		// Map entity per EntityType
		$mapEntities = array();
		foreach ($entities as $entity) {
			$mapEntities[strtolower($entity->getType()->getValue())][$entity->getId()] = $entity;
		}

		$idsToFetch = array();
		foreach ($mapEntities as $entityType => $entityIds) {
			// Prepare the cache if not existing yet
			if (!array_key_exists($entityType, self::$ENTITY_INFO)) {
				self::$ENTITY_INFO[$entityType] = array();
			}

			foreach ($entityIds as $entityId => $entity) {
				// Forget if the character was already added
				if (array_key_exists($entityId, self::$ENTITY_INFO[$entityType])) {
					continue;
				}
				$idsToFetch[$entityType][] = intval($entityId);
			}
		}

		if (!empty($idsToFetch)) {
			foreach ($idsToFetch as $entityType => $entityIds) {
				// Sets the parameters
				$res = EsiFactory::invoke(
					null,
					"get",
					"/" . $entityType . "s/names/",
					array(),
					array($entityType . "_ids" => $entityIds)
				);
				$json = json_decode($res->raw, true);
				foreach ($json as $character) {
					$character = SimpleEntityInfo::create($character, new EntityType($entityType));
					if (array_key_exists($character->getId(), self::$ENTITY_INFO[$entityType])) {
						self::$ENTITY_INFO[$entityType][$character->getId()]->setName($character->getName());
					} else {
						self::$ENTITY_INFO[$entityType][$character->getId()] = $character;
					}
				}
			}
		}

		foreach ($idsToFetch as $entityType => $entityIds) {
			foreach ($entityIds as $entityId) {
				$entities[$entityId]->setName(self::$ENTITY_INFO[$entityType][$entityId]->getName());
			}
		}
		return $entities;
	}

}
