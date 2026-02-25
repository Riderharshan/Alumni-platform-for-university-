<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageFieldsToPostMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration *adds* production-ready columns to the existing `post_media` table:
     * - storage/delivery columns (disk, file_name, provider, provider_id)
     * - descriptive/meta columns (original_name, mime_type, thumbnail_url, alt_text)
     * - processing and ordering flags (is_processed, position)
     * - timestamps (updated_at) and useful indexes
     *
     * The migration is defensive: it checks for existing columns before adding them.
     *
     * Note: Avoid changing existing `url` column type here (requires doctrine/dbal) —
     * keep current url column as-is and use new file_name/disk/provider fields to derive URLs.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_media', function (Blueprint $table) {
            // storage/delivery
            if (!Schema::hasColumn('post_media', 'disk')) {
                $table->string('disk', 50)->default('local')->after('post_id')->comment('storage disk, e.g., s3, local');
            }
            if (!Schema::hasColumn('post_media', 'file_name')) {
                $table->string('file_name', 1024)->nullable()->after('url')->comment('path/key on disk');
            }
            if (!Schema::hasColumn('post_media', 'provider')) {
                $table->string('provider', 50)->nullable()->after('file_name')->comment('external provider e.g., cloudinary');
            }
            if (!Schema::hasColumn('post_media', 'provider_id')) {
                $table->string('provider_id', 255)->nullable()->after('provider')->comment('external provider id/public_id');
            }

            // original / descriptive
            if (!Schema::hasColumn('post_media', 'original_name')) {
                $table->string('original_name', 1024)->nullable()->after('file_name')->comment('original uploaded filename');
            }
            if (!Schema::hasColumn('post_media', 'mime_type')) {
                $table->string('mime_type', 100)->nullable()->after('original_name')->comment('mime type, e.g., image/jpeg');
            }
            if (!Schema::hasColumn('post_media', 'thumbnail_url')) {
                $table->string('thumbnail_url', 2048)->nullable()->after('mime_type')->comment('URL to generated thumbnail');
            }
            if (!Schema::hasColumn('post_media', 'alt_text')) {
                $table->string('alt_text', 512)->nullable()->after('thumbnail_url')->comment('alt text for accessibility/SEO');
            }

            // processing + ordering
            if (!Schema::hasColumn('post_media', 'position')) {
                $table->unsignedInteger('position')->default(0)->after('type')->comment('order within post');
            }
            if (!Schema::hasColumn('post_media', 'is_processed')) {
                $table->boolean('is_processed')->default(false)->after('meta')->comment('have thumbnails/resizes been generated?');
            }

            // timestamps: created_at likely exists; add updated_at if absent
            if (!Schema::hasColumn('post_media', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            // indexes
            // index on post_id already implied by FK, but explicit index is ok / useful
            if (!\Illuminate\Support\Facades\Schema::hasColumn('post_media', 'post_id_idx_dummy_marker')) {
                // Use raw index creation without adding a marker column — instead check indexes in DB isn't trivial,
                // so attempt to create indexes with "if not exists" semantics by catching exceptions is overkill.
                // We'll create indexes safely; if duplicate index names exist, the DB will error — keep index names unique.
            }
        });

        // Create indexes outside of Schema::table closure to keep code clean
        // Add index on post_id (if it doesn't already exist)
        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->index('post_id', 'idx_post_media_post_id');
            });
        } catch (\Throwable $e) {
            // ignore if index already exists or creation fails
        }

        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->index(['post_id', 'position'], 'idx_post_media_post_id_position');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->index('is_processed', 'idx_post_media_is_processed');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }

    /**
     * Reverse the migrations.
     *
     * Drops the columns added above. This will also drop the indexes created.
     *
     * @return void
     */
    public function down()
    {
        // Drop indexes first (guard with try/catch to avoid errors on missing indexes)
        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->dropIndex('idx_post_media_post_id');
            });
        } catch (\Throwable $e) {
            // ignore
        }
        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->dropIndex('idx_post_media_post_id_position');
            });
        } catch (\Throwable $e) {
            // ignore
        }
        try {
            Schema::table('post_media', function (Blueprint $table) {
                $table->dropIndex('idx_post_media_is_processed');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('post_media', function (Blueprint $table) {
            if (Schema::hasColumn('post_media', 'disk')) {
                $table->dropColumn('disk');
            }
            if (Schema::hasColumn('post_media', 'file_name')) {
                $table->dropColumn('file_name');
            }
            if (Schema::hasColumn('post_media', 'provider')) {
                $table->dropColumn('provider');
            }
            if (Schema::hasColumn('post_media', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
            if (Schema::hasColumn('post_media', 'original_name')) {
                $table->dropColumn('original_name');
            }
            if (Schema::hasColumn('post_media', 'mime_type')) {
                $table->dropColumn('mime_type');
            }
            if (Schema::hasColumn('post_media', 'thumbnail_url')) {
                $table->dropColumn('thumbnail_url');
            }
            if (Schema::hasColumn('post_media', 'alt_text')) {
                $table->dropColumn('alt_text');
            }
            if (Schema::hasColumn('post_media', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('post_media', 'is_processed')) {
                $table->dropColumn('is_processed');
            }
            if (Schema::hasColumn('post_media', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
}
