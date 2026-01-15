<?php
/**
 * Base repository interface.
 *
 * @package TMASD\Signals\Dispatch\Contracts
 */

declare(strict_types=1);

namespace TMASD\Signals\Dispatch\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface for database repositories.
 *
 * Defines the contract for data access layer following
 * the Repository pattern for separation of concerns.
 */
interface RepositoryInterface {

	/**
	 * Get the database table name.
	 *
	 * @return string Full table name with prefix.
	 */
	public function get_table_name(): string;

	/**
	 * Find a record by ID.
	 *
	 * @param int $id Record ID.
	 * @return array<string, mixed>|null Record data or null if not found.
	 */
	public function find( int $id ): ?array;

	/**
	 * Get all records.
	 *
	 * @return array<int, array<string, mixed>> Array of records.
	 */
	public function all(): array;

	/**
	 * Insert a new record.
	 *
	 * @param array<string, mixed> $data Record data.
	 * @return int Inserted record ID.
	 */
	public function insert( array $data ): int;

	/**
	 * Update an existing record.
	 *
	 * @param int                  $id   Record ID.
	 * @param array<string, mixed> $data Data to update.
	 * @return bool True on success.
	 */
	public function update( int $id, array $data ): bool;

	/**
	 * Delete a record.
	 *
	 * @param int $id Record ID.
	 * @return bool True on success.
	 */
	public function delete( int $id ): bool;
}
