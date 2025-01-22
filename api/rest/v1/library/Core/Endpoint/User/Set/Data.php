<?php

class rest_v1_library_Core_Endpoint_User_Set_Data
{
    private array $data = [];
    private string $welcome_text = "Hallo und willkommen {FORENAME} {SURENAME},\nwir haben deine Daten erfolgreich gespeichert.\nDu kannst dich ab jetzt mit dem Benutzernamen {USERNAME} anmelden.\nViel Spaß!";

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function validate(): void
    {
        if (!isset($this->data['username']) || empty($this->data['username'])) {
            throw new Exception('BAD REQUEST (Missing username)', 400);
        }
        if (!isset($this->data['forename']) || empty($this->data['forename'])) {
            throw new Exception('BAD REQUEST (Missing forename)', 400);
        }
        if (!isset($this->data['surename']) || empty($this->data['surename'])) {
            throw new Exception('BAD REQUEST (Missing surename)', 400);
        }
    }

    public function execute(): array
    {
        $username = escapeInput($this->data['username']);
        $forename = escapeInput($this->data['forename']);
        $surename = escapeInput($this->data['surename']);
        return ['message' => $this->createWelcomeMessage2($username, $forename, $surename)];
    }

    private function createWelcomeMessage(string $username, string $forename, string $surename): string
    {
        $search     = ['{USERNAME}', '{FORENAME}', '{SURENAME}'];
        $replace    = [$username, ucfirst($forename), ucfirst($surename)];
        return str_replace($search, $replace, $this->welcome_text);
    }

    private function createWelcomeMessage2(string $username, string $forename, string $surename): string
    {
        $translate = ['{USERNAME}' => $username, '{FORENAME}' => ucfirst($forename), '{SURENAME}' => ucfirst($surename)];
        return strtr($this->welcome_text, $translate);
    }


    // Set other methods...
}
