<?php

namespace App\Test\Controller;

use App\Entity\Suggestion;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SuggestionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SuggestionRepository $repository;
    private string $path = '/suggestion/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Suggestion::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Suggestion index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'suggestion[createdDate]' => 'Testing',
            'suggestion[book]' => 'Testing',
            'suggestion[tag]' => 'Testing',
            'suggestion[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/suggestion/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Suggestion();
        $fixture->setCreatedDate('My Title');
        $fixture->setBook('My Title');
        $fixture->setTag('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Suggestion');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Suggestion();
        $fixture->setCreatedDate('My Title');
        $fixture->setBook('My Title');
        $fixture->setTag('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'suggestion[createdDate]' => 'Something New',
            'suggestion[book]' => 'Something New',
            'suggestion[tag]' => 'Something New',
            'suggestion[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/suggestion/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCreatedDate());
        self::assertSame('Something New', $fixture[0]->getBook());
        self::assertSame('Something New', $fixture[0]->getTag());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Suggestion();
        $fixture->setCreatedDate('My Title');
        $fixture->setBook('My Title');
        $fixture->setTag('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/suggestion/');
    }
}
