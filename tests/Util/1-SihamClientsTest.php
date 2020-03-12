<?php

namespace App\Tests\Util;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SihamClientsTest extends KernelTestCase {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager('siham');
    }

    public function testSihamIsUp()
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT COUNT(*) FROM HR.ZYYP';
        $statement = $conn->prepare($sql);
        $statementIsUp = $statement->execute();
        $this->assertEquals(true, $statementIsUp);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}