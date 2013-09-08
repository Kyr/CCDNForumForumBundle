<?php

/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Tests\Repository;

use CCDNForum\ForumBundle\Tests\TestBase;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

class SubscriptionRepositoryTest extends TestBase
{
	public function testFindAllSubscriptionsForUserById()
	{
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllSubscriptionsForUserById');
		$category = $this->addNewCategory('testFindAllSubscriptionsForUserById', 1, $forum);
		$board = $this->addNewBoard('testFindAllSubscriptionsForUserById', 'testFindAllSubscriptionsForUserById', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], false);
		
		$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsForUserById($users['tom']->getId(), true);
		
		$this->assertNotNull($subscriptionsFound);
		$this->assertCount(3, $subscriptionsFound);
	}

	public function testFindAllSubscriptionsForTopicById()
	{
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllSubscriptionsForUserById');
		$category = $this->addNewCategory('testFindAllSubscriptionsForUserById', 1, $forum);
		$board = $this->addNewBoard('testFindAllSubscriptionsForUserById', 'testFindAllSubscriptionsForUserById', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], false);
		
		$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsForTopicById($topics[0]->getId(), true);
		
		$this->assertNotNull($subscriptionsFound);
		$this->assertCount(1, $subscriptionsFound);
	}

	public function testFindAllSubscriptionsPaginatedForUserById()
	{
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllTopicsPaginatedByBoardId');
		$category = $this->addNewCategory('testFindAllTopicsPaginatedByBoardId', 1, $forum);
		$board = $this->addNewBoard('testFindAllTopicsPaginatedByBoardId', 'testFindAllTopicsPaginatedByBoardId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], false);
		
		$pager = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsPaginatedForUserById($users['tom']->getId(), 1, 'unread', false);
		
		$subscriptionsFound = $pager->getItems();
		
		$this->assertCount(3, $subscriptionsFound);
		
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], true);
		
		$pager = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsPaginatedForUserById($users['tom']->getId(), 1, 'read', false);
		
		$subscriptionsFound = $pager->getItems();
		
		$this->assertCount(3, $subscriptionsFound);
	}

	public function testFindAllSubscriptionsPaginatedForUserByIdAndForumById()
	{
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllTopicsPaginatedByBoardId');
		$category = $this->addNewCategory('testFindAllTopicsPaginatedByBoardId', 1, $forum);
		$board = $this->addNewBoard('testFindAllTopicsPaginatedByBoardId', 'testFindAllTopicsPaginatedByBoardId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], false);
		
		$pager = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsPaginatedForUserByIdAndForumById($forum->getId(), $users['tom']->getId(), 1, 'unread', false);
		
		$subscriptionsFound = $pager->getItems();

		$this->assertCount(3, $subscriptionsFound);
		
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], true);
		
		$pager = $this->getSubscriptionModel()->getRepository()->findAllSubscriptionsPaginatedForUserByIdAndForumById($forum->getId(), $users['tom']->getId(), 1, 'read', false);
		
		$subscriptionsFound = $pager->getItems();

		$this->assertCount(3, $subscriptionsFound);
	}

    public function testFindOneSubscriptionForTopicByIdAndUserById()
    {
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllTopicsPaginatedByBoardId');
		$category = $this->addNewCategory('testFindAllTopicsPaginatedByBoardId', 1, $forum);
		$board = $this->addNewBoard('testFindAllTopicsPaginatedByBoardId', 'testFindAllTopicsPaginatedByBoardId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts($topics, $users['tom']);
		$subscriptions = $this->addFixturesForSubscriptions($forum, $topics, $users['tom'], false);
		
	    $subscriptionFound = $this->getSubscriptionModel()->getRepository()->findOneSubscriptionForTopicByIdAndUserById($topics[0]->getId(), $users['tom']->getId());

		$this->assertNotNull($subscriptionFound);
		$this->assertTrue($subscriptionFound->isSubscribed());
		$this->assertInternalType('integer', $subscriptionFound->getId());
    }

    public function testCountSubscriptionsForTopicById()
    {
		$this->purge();
		
		$users = $this->addFixturesForUsers();
		$forum = $this->addNewForum('testFindAllTopicsPaginatedByBoardId');
		$category = $this->addNewCategory('testFindAllTopicsPaginatedByBoardId', 1, $forum);
		$board = $this->addNewBoard('testFindAllTopicsPaginatedByBoardId', 'testFindAllTopicsPaginatedByBoardId', 1, $category);
		$topics = $this->addFixturesForTopics(array($board));
		$posts = $this->addFixturesForPosts(array($topics[0]), $users['tom']);
		
		$this->addFixturesForSubscriptions($forum, array($topics[0]), $users['tom'], false);
		$this->addFixturesForSubscriptions($forum, array($topics[0]), $users['dick'], false);
		$this->addFixturesForSubscriptions($forum, array($topics[0]), $users['harry'], false);
		
    	$subscriptionsFound = $this->getSubscriptionModel()->getRepository()->countSubscriptionsForTopicById($topics[0]->getId());
		
		$this->assertSame(3, (int) $subscriptionsFound);
    }
}