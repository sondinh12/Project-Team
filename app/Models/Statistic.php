<?php
namespace App\Models;


class Statistic extends Model {
    public function statistic($start_date, $end_date)
{
    $stmt = $this->queryBuilder
        ->select(
            'COALESCE(SUM(total), 0) AS total',
            'COALESCE(COUNT(*), 0) AS total_orders',
            'COALESCE(SUM(CASE WHEN status = "cancelled" THEN "confirmed" ELSE "pending" END), 0) AS canceled_orders'
        )
        ->from('orders')
        ->where('created_at BETWEEN :start_date AND :end_date')
        ->setParameter('start_date', $start_date)
        ->setParameter('end_date', $end_date);

    return $this->connection->fetchAssociative($stmt->getSQL(), $stmt->getParameters());
}

}
?>