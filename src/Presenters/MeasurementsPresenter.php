<?php

namespace Crm\DashboardModule\Presenters;

use Crm\AdminModule\Presenters\AdminPresenter;
use Crm\ApplicationModule\Components\Graphs\GoogleLineGraphGroup\GoogleLineGraphGroupControlFactoryInterface;
use Crm\ApplicationModule\Models\Graphs\Criteria;
use Crm\ApplicationModule\Models\Graphs\GraphDataItem;
use Crm\ApplicationModule\Models\Graphs\Scale\Measurements\RangeScaleFactory;
use Crm\ApplicationModule\Repositories\MeasurementsRepository;
use Nette\Application\Attributes\Persistent;
use Nette\Utils\DateTime;

class MeasurementsPresenter extends AdminPresenter
{
    private MeasurementsRepository $measurementsRepository;

    #[Persistent]
    public string $dateFrom;

    #[Persistent]
    public string $dateTo;

    public function __construct(
        MeasurementsRepository $measurementsRepository,
    ) {
        parent::__construct();
        $this->measurementsRepository = $measurementsRepository;
    }

    public function startup()
    {
        parent::startup();

        $this->dateFrom = $this->dateFrom ?? DateTime::from('-2 months')->format('Y-m-d');
        $this->dateTo = $this->dateTo ?? DateTime::from('today')->format('Y-m-d');
    }

    public function renderDefault(string $id = null)
    {
        if ($id) {
            $measurementRow = $this->measurementsRepository->findByCode($id);
            $this->template->measurementRow = $measurementRow;
        }

        $this->template->measurements = $this->measurementsRepository->all();
    }

    public function createComponentMeasurementChart(GoogleLineGraphGroupControlFactoryInterface $factory)
    {
        $measurement = $this->measurementsRepository->findByCode($this->getParameter('id'));

        $criteria = new Criteria();
        $criteria
            ->setSeries($measurement->code)
            ->setStart($this->dateFrom)
            ->setEnd($this->dateTo)
        ;

        $graphDataItem = new GraphDataItem();
        $graphDataItem->setCriteria($criteria)
            ->setScaleProvider(RangeScaleFactory::PROVIDER_MEASUREMENT)
            ->setName($this->translator->translate($measurement->title));

        $control = $factory->create();

        $control->setGraphTitle($this->translator->translate($measurement->title))
            ->setGraphHelp($this->translator->translate($measurement->description))
            ->addGraphDataItem($graphDataItem);

        return $control;
    }
}
