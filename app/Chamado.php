<?php

class Chamado
{
    private const PRIORIDADES = ['baixa', 'media', 'alta'];
    private const STATUS      = ['aberto', 'andamento', 'fechado'];

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function contarTotal(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM chamados")->fetchColumn();
    }

    public function limitarTotal(int $maximo): void
    {
        if ($maximo <= 0) {
            return;
        }

        $total = $this->contarTotal();

        if ($total <= $maximo) {
            return;
        }

        $excedente = $total - $maximo;

        $stmt = $this->pdo->prepare(
            "DELETE FROM chamados
              ORDER BY data_criacao ASC, id ASC
              LIMIT :excedente"
        );
        $stmt->bindValue(':excedente', $excedente, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function contarPorStatus(): array
    {
        $sql  = "SELECT status, COUNT(*) AS total FROM chamados GROUP BY status";
        $rows = $this->pdo->query($sql)->fetchAll();

        $contadores = array_fill_keys(self::STATUS, 0);

        foreach ($rows as $r) {
            $contadores[$r['status']] = (int) $r['total'];
        }

        return $contadores;
    }

    public function listar(string $status = '', string $busca = '', string $ordem = 'desc'): array
    {
        $sql    = "SELECT * FROM chamados WHERE 1=1";
        $params = [];

        if (in_array($status, self::STATUS, true)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($busca !== '') {
            $termo = str_replace(['%', '_'], ['\\%', '\\_'], $busca);
            $sql .= " AND (titulo LIKE :b1 OR descricao LIKE :b2)";
            $params[':b1'] = "%{$termo}%";
            $params[':b2'] = "%{$termo}%";
        }

        $dir  = $ordem === 'asc' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY data_criacao {$dir}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chamados WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function criar(array $dados): void
    {
        $dados = $this->sanitizar($dados);

        $this->pdo->prepare(
            "INSERT INTO chamados (titulo, descricao, prioridade, status)
             VALUES (:titulo, :descricao, :prioridade, :status)"
        )->execute($dados);
    }

    public function atualizar(int $id, array $dados): void
    {
        $dados       = $this->sanitizar($dados);
        $dados[':id'] = $id;

        $this->pdo->prepare(
            "UPDATE chamados
                SET titulo = :titulo, descricao = :descricao,
                    prioridade = :prioridade, status = :status
              WHERE id = :id"
        )->execute($dados);
    }

    public function excluir(int $id): void
    {
        $this->pdo->prepare("DELETE FROM chamados WHERE id = :id")
                   ->execute([':id' => $id]);
    }

    private function sanitizar(array $d): array
    {
        $pri = in_array($d['prioridade'] ?? '', self::PRIORIDADES, true)
             ? $d['prioridade'] : 'baixa';

        $sts = in_array($d['status'] ?? '', self::STATUS, true)
             ? $d['status'] : 'aberto';

        return [
            ':titulo'     => trim($d['titulo']    ?? ''),
            ':descricao'  => trim($d['descricao'] ?? ''),
            ':prioridade' => $pri,
            ':status'     => $sts,
        ];
    }
}
