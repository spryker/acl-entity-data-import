<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\AclEntityDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Orm\Zed\Acl\Persistence\SpyAclRole;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\AclEntity\Persistence\Base\SpyAclEntitySegmentQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRule;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\Merchant\Persistence\SpyAclEntitySegmentMerchantQuery;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\Product\Persistence\Base\SpyProductQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AclEntityDataImportCommunicationTester extends Actor
{
    use _generated\AclEntityDataImportCommunicationTesterActions;

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_REFERENCE_1 = 'sH9qLMZtt6sxWqRJVYib';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_REFERENCE_2 = '5nIYY1SETa50lSDiwxf8';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NAME_1 = 'Segment 1';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_NAME_2 = 'Segment 2';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TARGET_ENTITY = 'Orm\Zed\Merchant\Persistence\SpyMerchant';

    public function deleteAclEntitySegments(): void
    {
        $this->getAclEntitySegmentQuery()->filterByReference_In(
            [
                static::ACL_ENTITY_SEGMENT_REFERENCE_1,
                static::ACL_ENTITY_SEGMENT_REFERENCE_2,
            ],
        )->delete();
    }

    public function getAclEntitySegmentQuery(): SpyAclEntitySegmentQuery
    {
        return SpyAclEntitySegmentQuery::create();
    }

    public function getProductConcreteQuery(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }

    public function cleanUpEntitySegmentConnectors(): void
    {
        SpyAclEntitySegmentMerchantQuery::create()->find()->delete();
    }

    public function getAclEntityRule(int $fkAclRole, string $entity, string $scope): SpyAclEntityRule
    {
        return $this->getAclEntityRuleQuery()
            ->filterByFkAclRole($fkAclRole)
            ->filterByEntity($entity)
            ->filterByScope($scope)
            ->findOne();
    }

    public function getAclEntityRuleQuery(): SpyAclEntityRuleQuery
    {
        return SpyAclEntityRuleQuery::create();
    }

    public function getAclRole(string $reference): SpyAclRole
    {
        return $this->getAclRoleQuery()->findOneByReference($reference);
    }

    public function getAclRoleQuery(): SpyAclRoleQuery
    {
        return SpyAclRoleQuery::create();
    }

    public function generateAclEntitySegments(): void
    {
        $merchantTransfer1 = $this->haveMerchant();
        $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer1->getIdMerchant()],
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_NAME_1,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_REFERENCE_1,
            ],
        );

        $merchantTransfer2 = $this->haveMerchant();
        $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::ENTITY => static::ACL_ENTITY_SEGMENT_TARGET_ENTITY,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer2->getIdMerchant()],
                AclEntitySegmentTransfer::NAME => static::ACL_ENTITY_SEGMENT_NAME_2,
                AclEntitySegmentTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_REFERENCE_2,
            ],
        );
    }

    /**
     * @param array<string> $merchantReferences
     *
     * @return void
     */
    public function deleteMerchants(array $merchantReferences): void
    {
        SpyMerchantQuery::create()->filterByMerchantReference_In($merchantReferences)->delete();
    }
}
