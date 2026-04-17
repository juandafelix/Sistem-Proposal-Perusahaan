select
    ac.aff_id,
    sum(ac.amount) as total_belum_dibayar
    from aff_commission ac
    left join aff_payout_detail apd on ac.payout_detail_id = apd.payout_detail_id
    where ac.is_voided = 0
    and (ac.payout_detail_id is null or apd.is_paid = 0)
    group by ac.aff_id;

